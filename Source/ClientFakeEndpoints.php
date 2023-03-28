<?php

namespace Dbt\ClientFake;

use Dbt\ClientFake\Traits\AsData;

class ClientFakeEndpoints
{
    use AsData;

    public function __construct(protected ClientFake $clientFake)
    {
    }

    public function fake()
    {

    }

    public function done(): ClientFake
    {
        return $this->clientFake;
    }
}
