<?php

namespace Dbt\ClientFake\Tests\Options;

use Dbt\ClientFake\Options\Options;
use Dbt\ClientFake\TestDoubles\CatFacts;
use Dbt\ClientFake\Tests\TestCase;

class OptionsTest extends TestCase
{
    /** @test */
    public function getting_a_url_with_a_version(): void
    {
        $options = new Options(
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
        $options = new Options(
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
