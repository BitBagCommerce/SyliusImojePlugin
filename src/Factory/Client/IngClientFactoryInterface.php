<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Client;

use BitBag\SyliusIngPlugin\Client\IngApiClient;

interface IngClientFactoryInterface
{
    public function getClient(string $code): IngApiClient;
}
