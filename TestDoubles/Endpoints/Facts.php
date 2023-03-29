<?php

namespace Dbt\ClientFake\TestDoubles\Endpoints;

use Dbt\ClientFake\Endpoints\Endpoints;

/**
 * @method \Dbt\ClientFake\TestDoubles\CatFactsFake done()
 * @method \Dbt\ClientFake\TestDoubles\CatFactsFake with(\Closure $closure)
 */
class Facts extends Endpoints
{
    public function show(string $fact): self
    {
        return $this->fake('fact', ['fact' => $fact]);
    }

    public function faker(): self
    {
    }
}
