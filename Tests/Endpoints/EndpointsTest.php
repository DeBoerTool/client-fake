<?php

/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUnhandledExceptionInspection */

namespace Dbt\ClientFake\Tests\Endpoints;

use Dbt\ClientFake\TestDoubles\CatFactsFake;
use Dbt\ClientFake\TestDoubles\Endpoints\Breeds;
use Dbt\ClientFake\Tests\TestCase;
use Throwable;

class EndpointsTest extends TestCase
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
            Breeds::class,
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
            fn (Breeds $breeds) => $breeds->index([]),
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
            ->breeds->with(fn (Breeds $eps) => $eps->index($breeds))
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
            ->breeds(fn (Breeds $eps) => $eps->index($breeds))
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
        $factsResponse = $this->service()->getFact();

        $this->assertSame($breeds, $breedsResponse->json('data'));
        $this->assertFakeResponse($breedsResponse);

        $this->assertSame($fact, $factsResponse->json('fact'));
        $this->assertFakeResponse($factsResponse);
    }
}
