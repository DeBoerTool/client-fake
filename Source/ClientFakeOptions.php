<?php

namespace Dbt\ClientFake;

/**
 * A class that holds the options for the ClientFakeAbstract. How you populate
 * this is up to you. Usually these values come from your services config file.
 */
class ClientFakeOptions
{
    /**
     * @param string $service The fully-qualified class name of the service.
     * @param string $base The base URL of the API.
     * @param string|false $version The version of the API. Set to false to
     *                              disable versioning.
     */
    public function __construct(
        public readonly string $service,
        public readonly string $base,
        public string|false $version = 'v1',
        public readonly array $headers = ['Fake' => 'true'],
    ) {
    }

    /**
     * @param string|array $fragment The URL fragment to append to the base URL.
     *                               If an array is passed in, the first element
     *                               will be used as the format string, and the
     *                               remaining elements will be used as the
     *                               arguments.
     */
    public function url(string|array $fragment): string
    {
        $url = is_array($fragment)
            ? sprintf(...$fragment)
            : $fragment;

        if ($this->version) {
            return sprintf('%s/%s/%s', $this->base, $this->version, $url);
        }

        return sprintf('%s/%s', $this->base, $url);
    }
}
