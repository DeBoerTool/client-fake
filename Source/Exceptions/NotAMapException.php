<?php

namespace Dbt\ClientFake\Exceptions;

use Exception;

class NotAMapException extends Exception
{
    public const FORMAT = '%s must be an associative array of %s.';

    public static function of(string $type, string $expected): self
    {
        return new self(sprintf(self::FORMAT, $type, $expected));
    }

    /**
     * @throws \Dbt\ClientFake\Exceptions\NotAMapException
     */
    public static function check(array $array, string $type, string $expected): void
    {
        if (!empty($array) && array_is_list($array)) {
            throw self::of($type, $expected);
        }
    }
}
