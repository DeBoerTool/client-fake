<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Dbt\ClientFake\Tests;

use Dbt\ClientFake\Tests\Fakes\BreedEps;
use Dbt\ClientFake\Tests\Fakes\CatFactsFake;
use Throwable;

class ClientFakeEndpointsTest extends TestCase
{
    /** @test */
    public function failing_to_get_the_endpoints(): void
    {
        try {
            $this->fake()->somethingThatDoesntExist;
        } catch (Throwable $e) {
            $this->assertStringContainsString(
                'Undefined property',
                $e->getMessage(),
            );

            return;
        }

        $this->fail('No exception was thrown.');
    }

    /** @test */
    public function getting_the_endpoints(): void
    {
        $this->assertInstanceOf(
            BreedEps::class,
            $this->fake()->breeds,
        );
    }

    /** @test */
    public function failing_to_call_the_endpoints(): void
    {
        try {
            $this->fake()->aMethodThatDoesNotExist();
        } catch (Throwable $e) {
            $this->assertStringContainsString(
                'Undefined method',
                $e->getMessage(),
            );

            return;
        }

        $this->fail('No exception was thrown.');
    }

    /** @test */
    public function calling_the_endpoints(): void
    {
        $fake = $this->fake()->breeds(
            fn (BreedEps $breeds) => $breeds->index([]),
        );

        $this->assertArrayHasKey(
            'https://catfact.ninja/breeds',
            $fake->fakes(),
        );
        $this->assertInstanceOf(CatFactsFake::class, $fake);
    }

    /** @test */
    public function using_the_endpoints(): void
    {
        $breeds = $this->breeds();
        $fact = $this->fact();

        $this->fake()->breeds
            ->index($breeds)
            ->done()
            ->getFact($fact)
            ->commit();

        $this->assertSame(
            $breeds,
            $this->service()->getBreeds()->json('data'),
        );

        $this->assertSame(
            $fact,
            $this->service()->getFact()->json('fact'),
        );
    }

    /** @test */
    public function using_a_closure(): void
    {
        $breeds = $this->breeds();
        $fact = $this->fact();

        $this->fake()
            ->breeds->with(fn (BreedEps $eps) => $eps->index($breeds))
            ->getFact($fact)
            ->commit();

        $this->assertSame(
            $breeds,
            $this->service()->getBreeds()->json('data'),
        );

        $this->assertSame(
            $fact,
            $this->service()->getFact()->json('fact'),
        );
    }

    /** @test */
    public function using_with_invoke(): void
    {
        $breeds = $this->breeds();
        $fact = $this->fact();

        $this->fake()
            ->breeds(fn (BreedEps $eps) => $eps->index($breeds))
            ->getFact($fact)
            ->commit();

        $this->assertSame(
            $breeds,
            $this->service()->getBreeds()->json('data'),
        );

        $this->assertSame(
            $fact,
            $this->service()->getFact()->json('fact'),
        );
    }

    /** @test */
    public function using_load(): void
    {
        $breeds = $this->breeds();
        $fact = $this->fact();

        $this->fake()
            ->with(['breeds.index', $breeds], ['facts.show', $fact])
            ->commit();

        $breedsResponse = $this->service()->getBreeds();

        $this->assertSame($breeds, $breedsResponse->json('data'));
        $this->assertFakeResponse($breedsResponse);

        $this->assertSame(
            $fact,
            $this->service()->getFact()->json('fact'),
        );
    }
}
