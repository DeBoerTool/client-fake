<?php

namespace Dbt\ClientFake\Providers;

interface ProviderInterface
{
    public function provide(): Generated;
}
