<?php

namespace Dbt\ClientFake\TestDoubles\Providers;

use Dbt\ClientFake\Providers\Generated;
use Dbt\ClientFake\Providers\ProviderInterface;
use Faker\Generator;

class FactsShow implements ProviderInterface
{
    public function __construct(public readonly Generator $faker)
    {
    }

    public function provide(): Generated
    {
        return new Generated($this->faker->words(5, true));
    }
}
