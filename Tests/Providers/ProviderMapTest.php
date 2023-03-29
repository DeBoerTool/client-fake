<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Dbt\ClientFake\Tests\Providers;

use Dbt\ClientFake\Exceptions\NotAMapException;
use Dbt\ClientFake\Providers\ProviderMap;
use Dbt\ClientFake\TestDoubles\Providers\FactsShow;
use Dbt\ClientFake\Tests\TestCase;

/**
 * @covers \Dbt\ClientFake\Providers\ProviderMap
 */
class ProviderMapTest extends TestCase
{
    /** @test */
    public function constructing(): void
    {
        $map = new ProviderMap(['fact.show' => FactsShow::class]);

        $this->assertInstanceOf(ProviderMap::class, $map);

        $map = new ProviderMap([]);

        $this->assertInstanceOf(ProviderMap::class, $map);

        $this->expectException(NotAMapException::class);

        new ProviderMap(['foo', 'bar']);
    }

    /** @test */
    public function putting_and_getting_and_has(): void
    {
        $map = new ProviderMap([]);

        $map->put('fact.show', FactsShow::class);

        $this->assertTrue($map->has('fact.show'));
        $this->assertSame(FactsShow::class, $map->get('fact.show'));
    }

    /** @test */
    public function making(): void
    {
        $map = new ProviderMap(['fact.show' => FactsShow::class]);

        $provider = $map->make('fact.show', $this->app);

        $this->assertInstanceOf(FactsShow::class, $provider);
    }
}
