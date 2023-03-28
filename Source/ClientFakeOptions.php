<?php

namespace Dbt\ClientFake;

/**
 * A class that defines the options for the ClientFake.
 */
class ClientFakeOptions implements ClientFakeOptionsInterface
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

    public function service(): string
    {
        return $this->service;
    }

    public function base(): string
    {
        return $this->base;
    }

    public function headers(): array
    {
        return $this->headers;
    }

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
