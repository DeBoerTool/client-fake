<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Dbt\ClientFake\Tests\Exceptions;

use Dbt\ClientFake\Exceptions\NotAMapException;
use Dbt\ClientFake\Tests\TestCase;

/**
 * @covers \Dbt\ClientFake\Exceptions\NotAMapException
 */
class NotAMapExceptionTest extends TestCase
{
    /** @test */
    public function not_a_map_exception(): void
    {
        try {
            throw NotAMapException::of('some.path', '[format]');
        } catch (NotAMapException $e) {
            $this->assertStringContainsString(
                'some.path',
                $e->getMessage(),
            );

            $this->assertStringContainsString(
                '[format]',
                $e->getMessage(),
            );
        }
    }

    /** @test */
    public function not_a_map_check(): void
    {
        NotAMapException::check(['foo' => 'bar'], 'foo', 'bar');
        NotAMapException::check([], 'foo', 'bar');

        $this->expectException(NotAMapException::class);

        NotAMapException::check(['foo', 'bar'], 'foo', 'bar');
    }
}
