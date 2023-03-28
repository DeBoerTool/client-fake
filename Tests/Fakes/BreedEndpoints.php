<?php

namespace Dbt\ClientFake\Tests\Fakes;

use Dbt\ClientFake\ClientFakeEndpoints;

class BreedEndpoints extends ClientFakeEndpoints
{
    public function getBreeds(array $breeds): self
    {
        return $this->fake('breeds', $this->asData($breeds));
    }
}
