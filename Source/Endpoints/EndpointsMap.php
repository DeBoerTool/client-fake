<?php

namespace Dbt\ClientFake\Endpoints;

use Dbt\ClientFake\ClientFake;
use Dbt\ClientFake\Exceptions\NoSuchEndpointsException;
use Dbt\ClientFake\Exceptions\NotAMapException;

class EndpointsMap
{
    private array $endpoints;

    /**
     * @throws \Dbt\ClientFake\Exceptions\NotAMapException
     */
    public function __construct(array $endpoints)
    {
        NotAMapException::check($endpoints, 'Endpoints', '[name => FQCN]');

        $this->endpoints = $endpoints;
    }

    /**
     * @throws \Dbt\ClientFake\Exceptions\NoSuchEndpointsException
     */
    public function get(string $key): string
    {
        return $this->endpoints[$key]
            ?? throw new NoSuchEndpointsException($key);
    }

    /**
     * @throws \Dbt\ClientFake\Exceptions\NoSuchEndpointsException
     */
    public function make(string $key, ClientFake $clientFake): Endpoints
    {
        return new ($this->get($key))($clientFake);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->endpoints);
    }
}
