<?php

namespace Dbt\ClientFake\Endpoints;

use Closure;
use Dbt\ClientFake\ClientFake;
use Dbt\ClientFake\Traits\AsData;

class Endpoints
{
    use AsData;

    public function __construct(protected ClientFake $clientFake)
    {
    }

    public function __invoke(Closure $closure): ClientFake
    {
        return $this->with($closure);
    }

    public function with(Closure $closure): ClientFake
    {
        $closure($this);

        return $this->clientFake;
    }

    public function fake(
        string|array $url,
        Closure|array $data,
        int $code = 200,
    ): self {
        $this->clientFake->fake($url, $data, $code);

        return $this;
    }

    public function call(string $method, mixed ...$args): void
    {
        $this->{$method}(...$args);
    }

    public function done(): ClientFake
    {
        return $this->clientFake;
    }
}
