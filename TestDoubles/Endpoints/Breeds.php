<?php

namespace Dbt\ClientFake\TestDoubles\Endpoints;

use Dbt\ClientFake\Endpoints\Endpoints;
use Dbt\ClientFake\Providers\Generated;

/**
 * @method \Dbt\ClientFake\TestDoubles\CatFactsFake done()
 * @method \Dbt\ClientFake\TestDoubles\CatFactsFake with(\Closure $closure)
 */
class Breeds extends Endpoints
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
