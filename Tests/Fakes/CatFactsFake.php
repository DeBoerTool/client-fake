<?php

namespace Dbt\ClientFake\Tests\Fakes;

use Dbt\ClientFake\ClientFake;
use Dbt\ClientFake\ClientFakeOptions;
use Illuminate\Contracts\Foundation\Application;

/**
 * The CatFacts Client Fake. This provides route faking capabilities for the
 * CatFacts Client.
 */
class CatFactsFake extends ClientFake
{
    public function __construct(Application $app)
    {
        parent::__construct($app, new ClientFakeOptions(
            CatFacts::class,
            'https://catfact.ninja',
            false,
        ));
    }

    public function getFact(string $fact): self
    {
        return $this->fake('fact', ['fact' => $fact]);
    }

    public function getBreeds(array $breeds): self
    {
        return $this->fake('breeds', $this->asData($breeds));
    }
}
