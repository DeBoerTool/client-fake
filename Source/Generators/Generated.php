<?php

namespace Dbt\ClientFake\Generators;

class Generated
{
    public readonly array $arguments;

    public function __construct(mixed ...$arguments)
    {
        $this->arguments = $arguments;
    }
}
