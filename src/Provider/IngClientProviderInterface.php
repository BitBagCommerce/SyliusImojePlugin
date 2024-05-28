<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider;

use BitBag\SyliusImojePlugin\Client\IngApiClientInterface;

interface IngClientProviderInterface
{
    public function getClient(string $code): IngApiClientInterface;
}
