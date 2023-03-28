<?php

namespace Dbt\ClientFake\Tests;

use Dbt\ClientFake\Tests\Fakes\CatFacts;
use Dbt\ClientFake\Tests\Fakes\CatFactsFake;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{
    protected function service(): CatFacts
    {
        return resolve(CatFacts::class);
    }

    protected function http(): Factory
    {
        return resolve(Factory::class);
    }

    protected function fake(): CatFactsFake
    {
        return resolve(CatFactsFake::class);
    }

    protected function fact(): string
    {
        return sprintf('This is a fake fact. %s.', Str::random());
    }

    protected function breeds(): array
    {
        return ['breed1', 'breed2'];
    }

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
