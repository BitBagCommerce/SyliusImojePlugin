<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Url;

use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;

interface UrlResolverInterface
{
    public function resolve(
        IngTransactionInterface $ingTransaction,
        IngClientConfigurationProviderInterface $ingClientConfiguration,
        IngClientProviderInterface $ingClientProvider
    ): string;
}
