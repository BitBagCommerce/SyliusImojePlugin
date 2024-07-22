<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Url;

use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientProviderInterface;

interface UrlResolverInterface
{
    public function resolve(
        ImojeTransactionInterface $imojeTransaction,
        ImojeClientConfigurationProviderInterface $imojeClientConfiguration,
        ImojeClientProviderInterface $imojeClientProvider,
    ): string;
}
