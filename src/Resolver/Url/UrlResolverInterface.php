<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Url;

use BitBag\SyliusImojePlugin\Entity\IngTransactionInterface;
use BitBag\SyliusImojePlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\IngClientProviderInterface;

interface UrlResolverInterface
{
    public function resolve(
        IngTransactionInterface $ingTransaction,
        IngClientConfigurationProviderInterface $ingClientConfiguration,
        IngClientProviderInterface $ingClientProvider
    ): string;
}
