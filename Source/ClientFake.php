<?php

namespace Dbt\ClientFake;

use Closure;
use Dbt\ClientFake\Traits\AsData;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\Factory as HttpClientFactory;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class ClientFake
{
    use AsData;

    protected bool $catchall = true;

    protected bool $enabled = true;

    protected array $fakes = [];

    protected readonly Generator $faker;

    private ClientFakeEndpointsCollection $endpoints;

    public function __construct(
        protected readonly Application $app,
        protected readonly ClientFakeOptionsInterface $options,
        Generator|null $faker = null,
        array $endpoints = [],
    ) {
        $this->faker = $faker ?? Factory::create();
        $this->endpoints = new ClientFakeEndpointsCollection($endpoints);
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
            is_array($data) ? $data : $this->app->call($data),
            $code,
            $this->options->headers(),
        );

        return $this;
    }

    public function with(array ...$calls): self
    {
        foreach ($calls as $call) {
            // Shift the path off the front of the array and explode it into
            // the endpoint name and method name.
            [$epName, $methodName] = explode('.', array_shift($call));

            // The rest of the array is the arguments to pass to the method.
            $this->endpoints->get($epName, $this)->{$methodName}(...$call);
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

    /**
     * @throws \Exception
     */
    public function __get(string $name)
    {
        if ($this->endpoints->has($name)) {
            return $this->endpoints->get($name, $this);
        }

        throw new Exception(sprintf(
            'Undefined property: %s::$%s',
            static::class,
            $name,
        ));
    }

    /**
     * @throws \Exception
     */
    public function __call(string $name, array $args)
    {
        if ($this->endpoints->has($name)) {
            return call_user_func(
                $this->endpoints->get($name, $this),
                ...$args,
            );
        }

        throw new Exception(sprintf(
            'Undefined method: %s::%s()',
            static::class,
            $name,
        ));
    }
}
