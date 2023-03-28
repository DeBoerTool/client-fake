<?php

namespace Dbt\ClientFake;

use InvalidArgumentException;

class ClientFakeEndpointsCollection
{
    private array $endpoints;

    public function __construct(array $endpoints)
    {
        if (array_is_list($endpoints)) {
            throw new InvalidArgumentException(
                'Endpoints must be an associative array of [name => FQCN].'
            );
        }

        $this->endpoints = $endpoints;
    }

    public function get(string $key, ClientFake $clientFake): ClientFakeEndpoints
    {
        /** @var \Dbt\ClientFake\ClientFakeEndpoints $fqcn */
        $fqcn = $this->endpoints[$key] ?? throw new InvalidArgumentException(
            sprintf('Endpoint "%s" does not exist.', $key)
        );

        return new $fqcn($clientFake);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->endpoints);
    }
}
