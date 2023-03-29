<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Dbt\ClientFake\Tests\Exceptions;

use Dbt\ClientFake\Exceptions\NoSuchProviderException;
use Dbt\ClientFake\Tests\TestCase;

/**
 * @covers \Dbt\ClientFake\Exceptions\NoSuchProviderException
 */
class NoSuchProviderExceptionTest extends TestCase
{
    /** @test */
    public function no_such_provider_exception(): void
    {
        $exception = NoSuchProviderException::of('some.path');

        $this->assertStringContainsString(
            'some.path',
            $exception->getMessage(),
        );
    }
}
