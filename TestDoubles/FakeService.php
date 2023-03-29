<?php

namespace Dbt\ClientFake\TestDoubles;

use Illuminate\Contracts\Foundation\Application;

class FakeService
{
    public function __construct(public Application $app)
    {
    }
}
