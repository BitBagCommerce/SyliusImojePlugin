<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Url;

use BitBag\SyliusIngPlugin\Client\IngApiClientInterface;
use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;

final class UrlResolver implements UrlResolverInterface
{
    public function resolve(
        IngTransactionInterface $ingTransaction,
        IngClientConfigurationProviderInterface $ingClientConfiguration,
        IngClientProviderInterface $ingClientProvider
    ): string {
        $code = $ingTransaction->getGatewayCode();
        $config = $ingClientConfiguration->getPaymentMethodConfiguration($code);
        $client = $ingClientProvider->getClient($code);

        return $this->createUrl($config, $ingTransaction, $client);
    }

    private function createUrl(
        IngClientConfigurationInterface $config,
        IngTransactionInterface $ingTransaction,
        IngApiClientInterface $client
    ): string {
        return \sprintf(
            '%s/%s/%s/%s',
            $config->getSandboxUrl(),
            $config->getMerchantId(),
            $client::TRANSACTION_ENDPOINT,
            $ingTransaction->getTransactionId()
        );
    }
}
