<?php

namespace Dbt\ClientFake;

/**
 * An interface that represents the required option for the ClientFake. You can
 * simply use the provided ClientFakeOptions object, but if you wish to define
 * your own, it must implement this interface.
 */
interface ClientFakeOptionsInterface
{
    /**
     * @param string|array $fragment The URL fragment to append to the base URL.
     *                               If an array is passed in, the first element will be used as the format
     *                               string, and the remaining elements will be used as the arguments.
     */
    public function url(string|array $fragment): string;

    /**
     * Get the fully-qualified class name of the service.
     */
    public function service(): string;

    /**
     * Get the array of headers to be added to fake requests.
     */
    public function headers(): array;
}
