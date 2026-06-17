<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Url;

use BitBag\SyliusIngPayPlugin\Client\IngPayApiClientInterface;
use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;
use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientConfigurationProviderInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientProviderInterface;

final class UrlResolver implements UrlResolverInterface
{
    public function resolve(
        IngPayTransactionInterface $ingPayTransaction,
        IngPayClientConfigurationProviderInterface $ingPayClientConfiguration,
        IngPayClientProviderInterface $ingPayClientProvider,
    ): string {
        $code = $ingPayTransaction->getGatewayCode();
        $config = $ingPayClientConfiguration->getPaymentMethodConfiguration($code);
        $client = $ingPayClientProvider->getClient($code);

        return $this->createUrl($config, $ingPayTransaction, $client);
    }

    private function createUrl(
        IngPayClientConfigurationInterface $config,
        IngPayTransactionInterface $ingPayTransaction,
        IngPayApiClientInterface $client,
    ): string {
        if ($config->isProd()) {
            return \sprintf(
                '%s/%s/%s/%s',
                $config->getProdUrl(),
                $config->getMerchantId(),
                $client::TRANSACTION_ENDPOINT,
                $ingPayTransaction->getTransactionId(),
            );
        }

        return \sprintf(
            '%s/%s/%s/%s',
            $config->getSandboxUrl(),
            $config->getMerchantId(),
            $client::TRANSACTION_ENDPOINT,
            $ingPayTransaction->getTransactionId(),
        );
    }
}
