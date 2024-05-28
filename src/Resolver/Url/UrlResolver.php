<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Url;

use BitBag\SyliusImojePlugin\Client\IngApiClientInterface;
use BitBag\SyliusImojePlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusImojePlugin\Entity\IngTransactionInterface;
use BitBag\SyliusImojePlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\IngClientProviderInterface;

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
        if ($config->isProd()) {
            return \sprintf(
                '%s/%s/%s/%s',
                $config->getProdUrl(),
                $config->getMerchantId(),
                $client::TRANSACTION_ENDPOINT,
                $ingTransaction->getTransactionId()
            );
        }

        return \sprintf(
            '%s/%s/%s/%s',
            $config->getSandboxUrl(),
            $config->getMerchantId(),
            $client::TRANSACTION_ENDPOINT,
            $ingTransaction->getTransactionId()
        );
    }
}
