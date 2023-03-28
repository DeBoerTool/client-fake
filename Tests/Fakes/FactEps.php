<?php

namespace Dbt\ClientFake\Tests\Fakes;

use Dbt\ClientFake\ClientFakeEndpoints;

/**
 * @method \Dbt\ClientFake\Tests\Fakes\CatFactsFake done()
 * @method \Dbt\ClientFake\Tests\Fakes\CatFactsFake with(\Closure $closure)
 */
class FactEps extends ClientFakeEndpoints
{
    public function show(string $fact): self
    {
        return $this->fake('fact', ['fact' => $fact]);
    }

    /**
     * Calling this generator will fail, as it should return a Generated
     * instance but instead returns an array.
     */
    public function showGenerator(): array
    {
        return [];
    }
}
