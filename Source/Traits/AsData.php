<?php

namespace Dbt\ClientFake\Traits;

trait AsData
{
    /**
     * Format an array as a child of the "data" key.
     */
    protected function asData(array $data): array
    {
        return ['data' => $data];
    }
}
