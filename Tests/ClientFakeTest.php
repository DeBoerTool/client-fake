<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Dbt\ClientFake\Tests;

use Dbt\ClientFake\Tests\Fakes\CatFacts;
use Dbt\ClientFake\Tests\Fakes\CatFactsFake;
use Dbt\ClientFake\Tests\Fakes\FakeService;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Str;

class ClientFakeTest extends TestCase
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

    /** @test */
    public function without_fakes(): void
    {
        $service = $this->service();

        $this->assertArrayHasKey('fact', $service->getFact()->json());
    }

    /** @test */
    public function with_fake(): void
    {
        $fact = $this->fact();
        $this->fake()->getFact($fact)->commit();

        $response = $this->service()->getFact();

        $this->assertFakeResponse($response);
        $this->assertSame($fact, $response->json('fact'));
    }

    /** @test */
    public function using_invoke_instead_of_commit(): void
    {
        $fact = $this->fact();
        $this->fake()->getFact($fact)();

        $response = $this->service()->getFact();

        $this->assertFakeResponse($response);
        $this->assertSame($fact, $response->json('fact'));
    }

    /** @test */
    public function with_catchall(): void
    {
        $fact = $this->fact();
        $this->fake()->getFact($fact)->commit();
        $service = $this->service();

        $this->assertSame($fact, $service->getFact()->json('fact'));

        $this->expectException(RequestException::class);

        // This throws a RequestException because this route has NOT been faked
        // and the CatFacts service calls the $response->throw() method on
        // each request.
        $service->getBreeds();
    }

    /** @test */
    public function without_catchall(): void
    {
        $fact = $this->fact();
        $this->fake()->getFact($fact)->withoutCatchall()->commit();
        $service = $this->service();

        $factResponse = $service->getFact();

        $this->assertSame($fact, $factResponse->json('fact'));
        $this->assertFakeResponse($factResponse);

        $breedsResponse = $service->getBreeds();

        // Since we've disabled the catchall, this request will NOT be faked.
        $this->assertIsArray($breedsResponse->json());
        $this->assertRealResponse($breedsResponse);

        // We can also enable the catchall again.
        $this->fake()->getFact($fact)->withCatchall()->commit();
        $this->expectException(RequestException::class);
        $this->service()->getBreeds();
    }

    /** @test */
    public function conditionally_disabling_and_enabling(): void
    {
        $this->fake()->getFact('fake')->enable(false)->commit();

        $this->assertRealResponse($this->service()->getFact());

        $this->fake()->getFact('fake')->enable(true)->commit();

        $this->assertFakeResponse($this->service()->getFact());
    }

    /** @test */
    public function conditionally_disabling_with_closure(): void
    {
        // This instance assertion tests that the closure's parameters are
        // resolved from the container.
        $callback = function (FakeService $service) {
            $this->assertInstanceOf(FakeService::class, $service);
        };

        $this->fake()->getFact('fake')->enable(false, $callback)->commit();

        $response = $this->service()->getFact();

        $this->assertRealResponse($response);
        // Assert that 2 assertions have been made. If the callback isn't
        // called, this assertion will fail.
        $this->assertSame(2, $this->getCount());
    }

    /** @test */
    public function data_wrapping(): void
    {
        $breeds = ['breed1', 'breed2'];
        $this->fake()->getBreeds($breeds)->commit();

        $response = $this->service()->getBreeds();

        $this->assertFakeResponse($response);
        $this->assertSame($breeds, $response->json('data'));
    }
}