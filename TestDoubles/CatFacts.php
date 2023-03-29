<?php

namespace Dbt\ClientFake\TestDoubles;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;

/**
 * The CatFacts Client. This is a simple wrapper around the Http Factory,
 * with a few methods to make it easier to use.
 */
class CatFacts
{
    public function __construct(public Factory $http)
    {
    }

    /**
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function getFact(): Response
    {
        return $this->get('https://catfact.ninja/fact');
    }

    /**
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function getBreeds(): Response
    {
        return $this->get('https://catfact.ninja/breeds');
    }

    /**
     * @throws \Illuminate\Http\Client\RequestException
     */
    protected function get(string $url): Response
    {
        return $this->http->get($url)->throw();
    }
}
