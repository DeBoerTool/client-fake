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
            $this->getFakeHeader($response),
            'No "Fake" header was found on the Response.',
        );
    }

    protected function assertRealResponse(Response $response): void
    {
        $this->assertNull(
            $this->getFakeHeader($response),
            'A "Fake" header was found on the Response.',
        );
    }

    private function getFakeHeader(Response $response): string|null
    {
        return $response->headers()['Fake'][0] ?? null;
    }
}
