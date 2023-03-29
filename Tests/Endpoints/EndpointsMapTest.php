<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Dbt\ClientFake\Tests\Endpoints;

use Dbt\ClientFake\Endpoints\EndpointsMap;
use Dbt\ClientFake\Exceptions\NoSuchEndpointsException;
use Dbt\ClientFake\Exceptions\NotAMapException;
use Dbt\ClientFake\TestDoubles\Endpoints\Breeds;
use Dbt\ClientFake\Tests\TestCase;

class EndpointsMapTest extends TestCase
{
    /** @test */
    public function constructing(): void
    {
        $map = new EndpointsMap([
            'breeds' => Breeds::class,
        ]);

        $this->assertInstanceOf(EndpointsMap::class, $map);

        // Empty maps are permitted.
        $map = new EndpointsMap([]);

        $this->assertInstanceOf(EndpointsMap::class, $map);

        $this->expectException(NotAMapException::class);

        new EndpointsMap([
            'since this is a list',
            'this will fail',
        ]);
    }

    /** @test */
    public function has_a_key(): void
    {
        $collection = new EndpointsMap([
            'breeds' => Breeds::class,
        ]);

        $this->assertTrue($collection->has('breeds'));
        $this->assertFalse($collection->has('something else'));
    }

    /** @test */
    public function getting_a_member(): void
    {
        $collection = new EndpointsMap([
            'breeds' => Breeds::class,
        ]);

        $this->assertInstanceOf(
            Breeds::class,
            $collection->make('breeds', $this->fake()),
        );

        $this->expectException(NoSuchEndpointsException::class);

        $collection->make('something else', $this->fake());
    }
}
