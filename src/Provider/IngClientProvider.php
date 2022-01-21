<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Provider;

use BitBag\SyliusIngPlugin\Client\IngApiClient;
use BitBag\SyliusIngPlugin\Factory\Serializer\SerializerFactory;
use GuzzleHttp\Client;

final class IngClientProvider implements IngClientProviderInterface
{
    private IngClientConfigurationProviderInterface $ingClientConfigurationProvider;

    private Client $httpClient;

    private SerializerFactory $serializerFactory;

    public function __construct(
        IngClientConfigurationProviderInterface $ingClientConfigurationProvider,
        Client $httpClient,
        SerializerFactory $serializerFactory
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
        $url = '';

        if ($configuration->isProd() === true) {
            $url = $configuration->getProdUrl();
        } else {
            $url = $configuration->getSandboxUrl();
        }
        $completeUrl = \sprintf('%s/%s/', $url, $merchantId);

        return new IngApiClient($this->httpClient, $this->serializerFactory, $token, $completeUrl);
    }
}
