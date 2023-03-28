<?php

namespace Dbt\ClientFake\Tests;
use Dbt\ClientFake\ClientFakeEndpointsCollection;
use Dbt\ClientFake\Tests\Fakes\BreedEndpoints;
use InvalidArgumentException;

class ClientFakeEndpointsCollectionTest extends TestCase
{
    /** @test */
    public function constructing (): void
    {
        $collection = new ClientFakeEndpointsCollection([
            'breeds' => BreedEndpoints::class,
        ]);

        $this->assertInstanceOf(
            ClientFakeEndpointsCollection::class,
            $collection,
        );

        $this->expectException(InvalidArgumentException::class);

        new ClientFakeEndpointsCollection([
            'since this is a list',
            'this will fail',
        ]);
    }

    /** @test */
    public function has_a_key (): void
    {
        $collection = new ClientFakeEndpointsCollection([
            'breeds' => BreedEndpoints::class,
        ]);

        $this->assertTrue($collection->has('breeds'));
        $this->assertFalse($collection->has('something else'));
    }

    /** @test */
    public function getting_a_member (): void
    {
        $collection = new ClientFakeEndpointsCollection([
            'breeds' => BreedEndpoints::class,
        ]);

        $this->assertInstanceOf(
            BreedEndpoints::class,
            $collection->get('breeds', $this->fake()),
        );

        $this->expectException(InvalidArgumentException::class);

        $collection->get('something else', $this->fake());
    }
}
