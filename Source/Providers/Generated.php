<?php

namespace Dbt\ClientFake\Providers;

class Generated
{
    public readonly array $arguments;

    public function __construct(mixed ...$arguments)
    {
        $this->arguments = $arguments;
    }
}
