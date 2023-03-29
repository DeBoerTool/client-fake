<?php

namespace Dbt\ClientFake\Providers;

use Dbt\ClientFake\Exceptions\NoSuchProviderException;
use Dbt\ClientFake\Exceptions\NotAMapException;
use Illuminate\Contracts\Foundation\Application;

class ProviderMap
{
    /** @var array{string, string} */
    private array $providers;

    /**
     * @throws \Dbt\ClientFake\Exceptions\NotAMapException
     */
    public function __construct(array $providers)
    {
        NotAMapException::check($providers, 'Providers', '[name => FQCN]');

        $this->providers = [];

        foreach ($providers as $path => $provider) {
            $this->put($path, $provider);
        }
    }

    public function put(string $key, string $provider): self
    {
        $this->providers[$key] = $provider;

        return $this;
    }

    /**
     * @throws \Dbt\ClientFake\Exceptions\NoSuchProviderException
     */
    public function get(string $key): string
    {
        return $this->providers[$key]
            ?? throw NoSuchProviderException::of($key);
    }

    /**
     * @throws \Dbt\ClientFake\Exceptions\NoSuchProviderException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function make(string $key, Application $app): ProviderInterface
    {
        return $app->make($this->get($key));
    }

    public function has(string $path): bool
    {
        return array_key_exists($path, $this->providers);
    }
}
