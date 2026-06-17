<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Provider;

use BitBag\SyliusIngPayPlugin\Client\IngPayApiClient;
use BitBag\SyliusIngPayPlugin\Factory\Serializer\SerializerFactoryInterface;
use BitBag\SyliusIngPayPlugin\Provider\RequestParams\RequestParamsProviderInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class IngPayClientProvider implements IngPayClientProviderInterface
{
    private IngPayClientConfigurationProviderInterface $ingPayClientConfigurationProvider;

    private ClientInterface $httpClient;

    private RequestParamsProviderInterface $requestParamsProvider;

    private SerializerFactoryInterface $serializerFactory;

    private RequestFactoryInterface $requestFactoryInterface;

    private StreamFactoryInterface $streamFactoryInterface;

    public function __construct(
        IngPayClientConfigurationProviderInterface $ingPayClientConfigurationProvider,
        ClientInterface $httpClient,
        RequestParamsProviderInterface $requestParamsProvider,
        SerializerFactoryInterface $serializerFactory,
        RequestFactoryInterface $requestFactoryInterface,
        StreamFactoryInterface $streamFactoryInterface,
    ) {
        $this->ingPayClientConfigurationProvider = $ingPayClientConfigurationProvider;
        $this->httpClient = $httpClient;
        $this->requestParamsProvider = $requestParamsProvider;
        $this->serializerFactory = $serializerFactory;
        $this->requestFactoryInterface = $requestFactoryInterface;
        $this->streamFactoryInterface = $streamFactoryInterface;
    }

    public function getClient(string $code): IngPayApiClient
    {
        $configuration = $this->ingPayClientConfigurationProvider->getPaymentMethodConfiguration($code);
        $token = $configuration->getToken();
        $merchantId = $configuration->getMerchantId();
        $url = $configuration->isProd() ? $configuration->getProdUrl() : $configuration->getSandboxUrl();

        $completeUrl = \sprintf('%s/%s/', $url, $merchantId);

        return new IngPayApiClient($this->httpClient, $this->requestParamsProvider, $this->serializerFactory->createSerializerWithNormalizer(), $token, $completeUrl, $this->requestFactoryInterface, $this->streamFactoryInterface);
    }
}
