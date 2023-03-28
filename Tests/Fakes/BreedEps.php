<?php

namespace Dbt\ClientFake\Tests\Fakes;

use Dbt\ClientFake\ClientFakeEndpoints;

/**
 * @method \Dbt\ClientFake\Tests\Fakes\CatFactsFake done()
 * @method \Dbt\ClientFake\Tests\Fakes\CatFactsFake with(\Closure $closure)
 */
class BreedEps extends ClientFakeEndpoints
{
    public function index(array $breeds): self
    {
        return $this->fake('breeds', $this->asData($breeds));
    }
}
