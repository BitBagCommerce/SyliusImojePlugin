<?php

namespace BitBag\SyliusIngPlugin\Factory\Serializer;

use BitBag\SyliusIngPlugin\Client\IngApiClient;

interface IngClientFactoryInterface
{
    public function getClient(string $code): IngApiClient;
}