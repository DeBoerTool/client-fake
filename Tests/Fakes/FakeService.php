<?php

namespace Dbt\ClientFake\Tests\Fakes;

use Illuminate\Contracts\Foundation\Application;

class FakeService
{
    public function __construct(public Application $app)
    {
    }
}
