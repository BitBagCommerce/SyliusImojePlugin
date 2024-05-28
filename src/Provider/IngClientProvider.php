<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider;

use BitBag\SyliusImojePlugin\Client\IngApiClient;
use BitBag\SyliusImojePlugin\Factory\Serializer\SerializerFactoryInterface;
use BitBag\SyliusImojePlugin\Provider\RequestParams\RequestParamsProviderInterface;
use GuzzleHttp\Client;

final class IngClientProvider implements IngClientProviderInterface
{
    private IngClientConfigurationProviderInterface $ingClientConfigurationProvider;

    private Client $httpClient;

    private RequestParamsProviderInterface $requestParamsProvider;

    private SerializerFactoryInterface $serializerFactory;

    public function __construct(
        IngClientConfigurationProviderInterface $ingClientConfigurationProvider,
        Client $httpClient,
        RequestParamsProviderInterface $requestParamsProvider,
        SerializerFactoryInterface $serializerFactory
    ) {
        $this->ingClientConfigurationProvider = $ingClientConfigurationProvider;
        $this->httpClient = $httpClient;
        $this->requestParamsProvider = $requestParamsProvider;
        $this->serializerFactory = $serializerFactory;
    }

    public function getClient(string $code): IngApiClient
    {
        $configuration = $this->ingClientConfigurationProvider->getPaymentMethodConfiguration($code);
        $token = $configuration->getToken();
        $merchantId = $configuration->getMerchantId();
        $url = $configuration->isProd() ? $configuration->getProdUrl() : $configuration->getSandboxUrl();

        $completeUrl = \sprintf('%s/%s/', $url, $merchantId);

        return new IngApiClient($this->httpClient, $this->requestParamsProvider, $this->serializerFactory->createSerializerWithNormalizer(), $token, $completeUrl);
    }
}
