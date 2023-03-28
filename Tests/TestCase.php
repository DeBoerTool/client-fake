<?php

namespace Dbt\ClientFake\Tests;

use Illuminate\Http\Client\Response;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{
    protected function assertFakeResponse(Response $response): void
    {
        $this->assertSame(
            'true',
            $response->header('Fake'),
            'No "Fake" header was found on the Response.',
        );
    }

    protected function assertRealResponse(Response $response): void
    {
        $this->assertSame(
            '',
            $response->header('Fake'),
            'A "Fake" header was found on the Response.',
        );
    }
}
