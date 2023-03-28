<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Dbt\ClientFake\Tests;

use Dbt\ClientFake\Tests\Fakes\BreedEndpoints;
use Throwable;

class ClientFakeEndpointsTest extends TestCase
{
    /** @test */
    public function failing_to_get_the_endpoints_object (): void
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
    public function getting_the_endpoints_object (): void
    {
        $this->assertInstanceOf(
            BreedEndpoints::class,
            $this->fake()->breeds,
        );
    }

    /** @test */
    public function using_the_endpoints (): void
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
}
