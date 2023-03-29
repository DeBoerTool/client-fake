<?php

namespace Dbt\ClientFake\Exceptions;

use Exception;

class NoSuchProviderException extends Exception
{
    public const FORMAT = 'No Provider found for key: "%s".';

    public static function of(string $key): self
    {
        return new self(sprintf(self::FORMAT, $key));
    }
}
