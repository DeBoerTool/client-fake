<?php

namespace Dbt\ClientFake\Tests\Providers;

use Dbt\ClientFake\Providers\Generated;
use Dbt\ClientFake\Tests\TestCase;

class GeneratedTest extends TestCase
{
    /** @test */
    public function getting_the_arguments(): void
    {
        $args = ['foo', 'bar', 'baz'];
        $generated = new Generated(...$args);

        $this->assertSame($args, $generated->arguments);
    }
}
