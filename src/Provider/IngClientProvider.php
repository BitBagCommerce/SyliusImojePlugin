<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Provider;

use BitBag\SyliusIngPlugin\Client\IngApiClient;
use BitBag\SyliusIngPlugin\Provider\RequestParams\RequestParamsProviderInterface;
use GuzzleHttp\Client;

final class IngClientProvider implements IngClientProviderInterface
{
    private IngClientConfigurationProviderInterface $ingClientConfigurationProvider;

    private Client $httpClient;

    private RequestParamsProviderInterface $requestParamsProvider;

    public function __construct(
        IngClientConfigurationProviderInterface $ingClientConfigurationProvider,
        Client $httpClient,
        RequestParamsProviderInterface $requestParamsProvider
    ) {
        $this->ingClientConfigurationProvider = $ingClientConfigurationProvider;
        $this->httpClient = $httpClient;
        $this->requestParamsProvider = $requestParamsProvider;
    }

    public function getClient(string $code): IngApiClient
    {
        $configuration = $this->ingClientConfigurationProvider->getPaymentMethodConfiguration($code);
        $token = $configuration->getToken();
        $merchantId = $configuration->getMerchantId();
        $url = $configuration->isProd() ? $configuration->getProdUrl() : $configuration->getSandboxUrl();

        $completeUrl = \sprintf('%s/%s/', $url, $merchantId);

        return new IngApiClient($this->httpClient, $this->requestParamsProvider, $token, $completeUrl);
    }
}
