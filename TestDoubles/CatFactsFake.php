<?php

namespace Dbt\ClientFake\TestDoubles;

use Dbt\ClientFake\ClientFake;
use Dbt\ClientFake\Options\Options;
use Dbt\ClientFake\TestDoubles\Endpoints\Breeds;
use Dbt\ClientFake\TestDoubles\Endpoints\Facts;
use Dbt\ClientFake\TestDoubles\Providers\FactsShow;
use Faker\Generator;
use Illuminate\Contracts\Foundation\Application;

/**
 * The CatFacts Client Fake. This provides route faking capabilities for the
 * CatFacts Client.
 *
 * @property \Dbt\ClientFake\TestDoubles\Endpoints\Breeds $breeds
 */
class CatFactsFake extends ClientFake
{
    public function __construct(Application $app)
    {
        $options = new Options(
            CatFacts::class,
            'https://catfact.ninja',
            false,
        );

        parent::__construct(
            app: $app,
            options: $options,
            endpoints: ['breeds' => Breeds::class, 'facts' => Facts::class],
            providers: ['facts.show' => FactsShow::class],
        );
    }

    public function getFact(string $fact): self
    {
        return $this->fake('fact', ['fact' => $fact]);
    }

    public function getFakerFact(): self
    {
        return $this->fake(
            'fact',
            fn (Generator $faker) => ['fact' => $faker->randomLetter()],
        );
    }

    public function getBreeds(array $breeds): self
    {
        return $this->fake('breeds', $this->asData($breeds));
    }
}
