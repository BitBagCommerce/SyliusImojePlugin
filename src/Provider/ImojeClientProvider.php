<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider;

use BitBag\SyliusImojePlugin\Client\ImojeApiClient;
use BitBag\SyliusImojePlugin\Factory\Serializer\SerializerFactoryInterface;
use BitBag\SyliusImojePlugin\Provider\RequestParams\RequestParamsProviderInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class ImojeClientProvider implements ImojeClientProviderInterface
{
    private ImojeClientConfigurationProviderInterface $imojeClientConfigurationProvider;

    private ClientInterface $httpClient;

    private RequestParamsProviderInterface $requestParamsProvider;

    private SerializerFactoryInterface $serializerFactory;

    private RequestFactoryInterface $requestFactoryInterface;
    private StreamFactoryInterface $streamFactoryInterface;

    public function __construct(
        ImojeClientConfigurationProviderInterface $imojeClientConfigurationProvider,
        ClientInterface $httpClient,
        RequestParamsProviderInterface $requestParamsProvider,
        SerializerFactoryInterface $serializerFactory,
        RequestFactoryInterface $requestFactoryInterface,
        StreamFactoryInterface $streamFactoryInterface
    ) {
        $this->imojeClientConfigurationProvider = $imojeClientConfigurationProvider;
        $this->httpClient = $httpClient;
        $this->requestParamsProvider = $requestParamsProvider;
        $this->serializerFactory = $serializerFactory;
        $this->requestFactoryInterface = $requestFactoryInterface;
        $this->streamFactoryInterface = $streamFactoryInterface;
    }

    public function getClient(string $code): ImojeApiClient
    {
        $configuration = $this->imojeClientConfigurationProvider->getPaymentMethodConfiguration($code);
        $token = $configuration->getToken();
        $merchantId = $configuration->getMerchantId();
        $url = $configuration->isProd() ? $configuration->getProdUrl() : $configuration->getSandboxUrl();

        $completeUrl = \sprintf('%s/%s/', $url, $merchantId);

        return new ImojeApiClient($this->httpClient, $this->requestParamsProvider, $this->serializerFactory->createSerializerWithNormalizer(), $token, $completeUrl, $this->requestFactoryInterface, $this->streamFactoryInterface);
    }
}
