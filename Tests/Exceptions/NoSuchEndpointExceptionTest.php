<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Dbt\ClientFake\Tests\Exceptions;

use Dbt\ClientFake\Exceptions\NoSuchEndpointsException;
use Dbt\ClientFake\Tests\TestCase;

/**
 * @covers \Dbt\ClientFake\Exceptions\NoSuchEndpointsException
 */
class NoSuchEndpointExceptionTest extends TestCase
{
    /** @test */
    public function static_of_and_message(): void
    {
        $exception = NoSuchEndpointsException::of('some.path');

        $this->assertStringContainsString(
            'some.path',
            $exception->getMessage(),
        );
    }
}
