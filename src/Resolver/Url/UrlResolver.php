<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Url;

use BitBag\SyliusImojePlugin\Client\ImojeApiClientInterface;
use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;
use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientProviderInterface;

final class UrlResolver implements UrlResolverInterface
{
    public function resolve(
        ImojeTransactionInterface                 $ingTransaction,
        ImojeClientConfigurationProviderInterface $ingClientConfiguration,
        ImojeClientProviderInterface $ingClientProvider
    ): string {
        $code = $ingTransaction->getGatewayCode();
        $config = $ingClientConfiguration->getPaymentMethodConfiguration($code);
        $client = $ingClientProvider->getClient($code);

        return $this->createUrl($config, $ingTransaction, $client);
    }

    private function createUrl(
        ImojeClientConfigurationInterface $config,
        ImojeTransactionInterface         $ingTransaction,
        ImojeApiClientInterface           $client
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
