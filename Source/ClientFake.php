<?php

namespace Dbt\ClientFake;

use Closure;
use Dbt\ClientFake\Endpoints\EndpointsMap;
use Dbt\ClientFake\Options\OptionsInterface;
use Dbt\ClientFake\Providers\ProviderMap;
use Dbt\ClientFake\Traits\AsData;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\Factory as HttpClientFactory;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class ClientFake
{
    use AsData;

    protected bool $catchall = true;

    protected bool $enabled = true;

    protected array $fakes = [];

    protected readonly Generator $faker;

    private EndpointsMap $endpoints;

    private ProviderMap $providers;

    /**
     * @throws \Dbt\ClientFake\Exceptions\NotAMapException
     */
    public function __construct(
        protected readonly Application $app,
        protected readonly OptionsInterface $options,
        Generator|null $faker = null,
        array $endpoints = [],
        array $providers = [],
    ) {
        $this->faker = $faker ?? Factory::create();
        $this->endpoints = new EndpointsMap($endpoints);
        $this->providers = new ProviderMap($providers);
    }

    public function __invoke(): self
    {
        return $this->commit();
    }

    /**
     * Conditionally enable/disable this fake. Also, if there is any setup that
     * needs to be done when the fake is disabled (such as creating records),
     * a setup callback may be passed it, and its dependencies will be resolved
     * from the container.
     */
    public function enable(bool $when, Closure|null $otherwise = null): self
    {
        $this->enabled = $when;

        if (!$when && $otherwise) {
            $this->app->call($otherwise);
        }

        return $this;
    }

    /**
     * Disable the catchall.
     */
    public function withoutCatchall(): self
    {
        $this->catchall = false;

        return $this;
    }

    /**
     * Enable the catchall.
     */
    public function withCatchall(): self
    {
        $this->catchall = true;

        return $this;
    }

    /**
     * Push the requested fakes up to the factory.
     *
     * When disabled, this method do nothing and return the instance.
     *
     * If the catchall is enabled, a "*" response will be added to the fakes
     * array. This will catch any stray requests that were not faked.
     */
    public function commit(): self
    {
        if (!$this->enabled) {
            return $this;
        }

        if ($this->catchall) {
            $this->fakes['*'] = fn (Request $request) => Http::response(
                sprintf(
                    '%s catchall caught this url: %s',
                    $this->options->service(),
                    $request->url(),
                ),
                500,
            );
        }

        // This could also be accomplished by simply binding a singleton to the
        // container. However, we're intending to target a specific service
        // here, so we use a focused binding. This means that other services
        // using the HTTP Client will not be affected.
        $this->app->when($this->options->service())
            ->needs(HttpClientFactory::class)
            ->give(fn () => (new HttpClientFactory())
                ->fake($this->fakes));

        return $this;
    }

    /**
     * Add a fake. The URL may be a simple string or an array in the format of
     * ['%s/%s', 'foo', 'bar'], which will be passed to sprintf. You do not
     * have to provide the full URL, just the path. The base URL and version
     * (if applicable) will be added automatically.
     *
     * The data may be a simple array or a closure that returns an array. If
     * the data is a closure, any dependencies will be resolved from the
     * container.
     *
     * The code is the HTTP status code to return.
     */
    public function fake(
        string|array $url,
        Closure|array $data,
        int $code = 200,
    ): self {
        $this->fakes[$this->url($url)] = HttpClientFactory::response(
            is_array($data)
                ? $data
                : $this->app->call($data),
            $code,
            $this->options->headers(),
        );

        return $this;
    }

    /**
     * @throws \Dbt\ClientFake\Exceptions\NoSuchProviderException
     * @throws \Dbt\ClientFake\Exceptions\NoSuchEndpointsException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function provide(array $paths): self
    {
        foreach ($paths as $path) {
            [$epName, $methodName] = explode('.', $path);

            $this->endpoints->make($epName, $this)->call(
                $methodName,
                ...$this->providers
                    ->make($path, $this->app)
                    ->provide()
                    ->arguments
            );
        }

        return $this;
    }

    /**
     * Add multiple fakes at once. Each fake is an array in the format of
     * ['endpoints.method', 'and', 'the params', 'to pass'].
     *
     * The method iterates through the list, constructing the given endpoints
     * class and calling the given method on that class, passing in whatever
     * parameters you have provided.
     *
     * @throws \Dbt\ClientFake\Exceptions\NoSuchEndpointsException
     */
    public function with(array ...$calls): self
    {
        $this->assertValidCallList($calls);

        foreach ($calls as $call) {
            // Shift the path off the front of the array and explode it into
            // the endpoint name and method name.
            [$ep, $method] = explode('.', array_shift($call));

            $this->endpoints->make($ep, $this)->call($method, ...$call);
        }

        return $this;
    }

    public function fakes(): array
    {
        return $this->fakes;
    }

    /**
     * Generate the full URL using the ClientFakeOptions URL generator.
     */
    protected function url(string|array $url): string
    {
        return $this->options->url($url);
    }

    protected function assertValidCallList(array $calls): void
    {
        $fail = fn (
            int $index,
            string $reason,
        ) => throw new InvalidArgumentException(
            sprintf('Invalid call [%s]: %s', $index, $reason),
        );

        foreach ($calls as $index => $call) {
            if (!array_is_list($call)) {
                $fail($index, 'Not a list.');
            }

            if (count($call) < 1) {
                $fail($index, 'Must have at least one element.');
            }

            if (!is_string($call[0])) {
                $fail($index, 'First element must be a string.');
            }

            if (!str_contains($call[0], '.')) {
                $fail(
                    $index,
                    'First element must be in the format of "endpoint.method".',
                );
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function __get(string $name)
    {
        if ($this->endpoints->has($name)) {
            return $this->endpoints->make($name, $this);
        }

        throw new Exception(
            sprintf(
                'Undefined property: %s::$%s',
                static::class,
                $name,
            )
        );
    }

    /**
     * @throws \Exception
     */
    public function __call(string $name, array $args)
    {
        if ($this->endpoints->has($name)) {
            return call_user_func(
                $this->endpoints->make($name, $this),
                ...$args,
            );
        }

        throw new Exception(
            sprintf(
                'Undefined method: %s::%s()',
                static::class,
                $name,
            )
        );
    }
}
