<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Provider;

use BitBag\SyliusIngPlugin\Client\IngApiClient;
use BitBag\SyliusIngPlugin\Factory\Serializer\SerializerFactoryInterface;
use GuzzleHttp\Client;

final class IngClientProvider implements IngClientProviderInterface
{
    private IngClientConfigurationProviderInterface $ingClientConfigurationProvider;

    private Client $httpClient;

    private SerializerFactoryInterface $serializerFactory;

    public function __construct(
        IngClientConfigurationProviderInterface $ingClientConfigurationProvider,
        Client $httpClient,
        SerializerFactoryInterface $serializerFactory
    ) {
        $this->ingClientConfigurationProvider = $ingClientConfigurationProvider;
        $this->httpClient = $httpClient;
        $this->serializerFactory = $serializerFactory;
    }

    public function getClient(string $code): IngApiClient
    {
        $configuration = $this->ingClientConfigurationProvider->getPaymentMethodConfiguration($code);
        $token = $configuration->getToken();
        $merchantId = $configuration->getMerchantId();
        $url = $configuration->isProd() ? $url = $configuration->getProdUrl() : $url = $configuration->getSandboxUrl();

        $completeUrl = \sprintf('%s/%s/', $url, $merchantId);

        return new IngApiClient($this->httpClient, $this->serializerFactory, $token, $completeUrl);
    }
}
