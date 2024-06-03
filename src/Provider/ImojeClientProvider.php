<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider;

use BitBag\SyliusImojePlugin\Client\ImojeApiClient;
use BitBag\SyliusImojePlugin\Factory\Serializer\SerializerFactoryInterface;
use BitBag\SyliusImojePlugin\Provider\RequestParams\RequestParamsProviderInterface;
use GuzzleHttp\Client;

final class ImojeClientProvider implements ImojeClientProviderInterface
{
    private ImojeClientConfigurationProviderInterface $imojeClientConfigurationProvider;

    private Client $httpClient;

    private RequestParamsProviderInterface $requestParamsProvider;

    private SerializerFactoryInterface $serializerFactory;

    public function __construct(
        ImojeClientConfigurationProviderInterface $imojeClientConfigurationProvider,
        Client                                    $httpClient,
        RequestParamsProviderInterface            $requestParamsProvider,
        SerializerFactoryInterface                $serializerFactory
    ) {
        $this->imojeClientConfigurationProvider = $imojeClientConfigurationProvider;
        $this->httpClient = $httpClient;
        $this->requestParamsProvider = $requestParamsProvider;
        $this->serializerFactory = $serializerFactory;
    }

    public function getClient(string $code): ImojeApiClient
    {
        $configuration = $this->imojeClientConfigurationProvider->getPaymentMethodConfiguration($code);
        $token = $configuration->getToken();
        $merchantId = $configuration->getMerchantId();
        $url = $configuration->isProd() ? $configuration->getProdUrl() : $configuration->getSandboxUrl();

        $completeUrl = \sprintf('%s/%s/', $url, $merchantId);

        return new ImojeApiClient($this->httpClient, $this->requestParamsProvider, $this->serializerFactory->createSerializerWithNormalizer(), $token, $completeUrl);
    }
}
