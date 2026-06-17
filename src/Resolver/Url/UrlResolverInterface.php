<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Url;

use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientConfigurationProviderInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientProviderInterface;

interface UrlResolverInterface
{
    public function resolve(
        IngPayTransactionInterface $ingPayTransaction,
        IngPayClientConfigurationProviderInterface $ingPayClientConfiguration,
        IngPayClientProviderInterface $ingPayClientProvider,
    ): string;
}
