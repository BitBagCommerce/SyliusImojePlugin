<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Provider;

use BitBag\SyliusIngPlugin\Client\IngApiClient;

interface IngClientProviderInterface
{
    public function getClient(string $code): IngApiClient;
}
