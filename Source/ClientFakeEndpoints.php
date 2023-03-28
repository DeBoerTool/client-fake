<?php

namespace Dbt\ClientFake;

use Closure;
use Dbt\ClientFake\Traits\AsData;

class ClientFakeEndpoints
{
    use AsData;

    public function __construct(protected ClientFake $clientFake)
    {
    }

    public function fake(
        string|array $url,
        Closure|array $data,
        int $code = 200,
    ): self {
        $this->clientFake->fake($url, $data, $code);

        return $this;
    }

    public function done(): ClientFake
    {
        return $this->clientFake;
    }
}
