<?php

namespace Dbt\ClientFake\Tests\Fakes;

use Dbt\ClientFake\ClientFakeEndpoints;
use Dbt\ClientFake\Generators\Generated;

/**
 * @method \Dbt\ClientFake\Tests\Fakes\CatFactsFake done()
 * @method \Dbt\ClientFake\Tests\Fakes\CatFactsFake with(\Closure $closure)
 */
class BreedEps extends ClientFakeEndpoints
{
    public const BREEDS = ['generated breed 1', 'generated breed 2'];

    public function index(array $breeds): self
    {
        return $this->fake('breeds', $this->asData($breeds));
    }

    public function indexGenerator(): Generated
    {
        return new Generated(self::BREEDS);
    }
}
