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
        ImojeTransactionInterface $imojeTransaction,
        ImojeClientConfigurationProviderInterface $imojeClientConfiguration,
        ImojeClientProviderInterface $imojeClientProvider,
    ): string {
        $code = $imojeTransaction->getGatewayCode();
        $config = $imojeClientConfiguration->getPaymentMethodConfiguration($code);
        $client = $imojeClientProvider->getClient($code);

        return $this->createUrl($config, $imojeTransaction, $client);
    }

    private function createUrl(
        ImojeClientConfigurationInterface $config,
        ImojeTransactionInterface $imojeTransaction,
        ImojeApiClientInterface $client,
    ): string {
        if ($config->isProd()) {
            return \sprintf(
                '%s/%s/%s/%s',
                $config->getProdUrl(),
                $config->getMerchantId(),
                $client::TRANSACTION_ENDPOINT,
                $imojeTransaction->getTransactionId(),
            );
        }

        return \sprintf(
            '%s/%s/%s/%s',
            $config->getSandboxUrl(),
            $config->getMerchantId(),
            $client::TRANSACTION_ENDPOINT,
            $imojeTransaction->getTransactionId(),
        );
    }
}
