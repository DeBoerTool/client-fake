<?php

namespace Dbt\ClientFake\Exceptions;

use Exception;

class NoSuchEndpointsException extends Exception
{
    public const FORMAT = 'No Endpoints found for key: "%s".';

    public static function of(string $path): self
    {
        return new self(sprintf(self::FORMAT, $path));
    }
}
