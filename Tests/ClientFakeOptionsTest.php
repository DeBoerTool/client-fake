<?php

namespace Dbt\ClientFake\Tests;

use Dbt\ClientFake\ClientFakeOptions;
use Dbt\ClientFake\Tests\Fakes\CatFacts;

class ClientFakeOptionsTest extends TestCase
{
    /** @test */
    public function getting_a_url_with_a_version(): void
    {
        $options = new ClientFakeOptions(
            CatFacts::class,
            'https://example.com',
        );

        $url1 = $options->url('some/endpoint');
        $url2 = $options->url(['some/%s', 'endpoint']);

        foreach ([$url1, $url2] as $url) {
            $this->assertSame(
                'https://example.com/v1/some/endpoint',
                $url,
            );
        }
    }

    /** @test */
    public function getting_a_url_without_a_version(): void
    {
        $options = new ClientFakeOptions(
            CatFacts::class,
            'https://example.com',
            false,
        );

        $url1 = $options->url('some/endpoint');
        $url2 = $options->url(['some/%s', 'endpoint']);

        foreach ([$url1, $url2] as $url) {
            $this->assertSame(
                'https://example.com/some/endpoint',
                $url,
            );
        }
    }
}
