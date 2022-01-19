<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Serializer;

use BitBag\SyliusIngPlugin\Client\IngApiClient;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use GuzzleHttp\Client;

final class IngClientFactory implements IngClientFactoryInterface
{
    private Client $httpClient;

    private SerializerFactory $serializerFactory;

    private IngClientConfigurationProviderInterface $ingClientConfiguration;

    public function __construct(
        Client $httpClient,
        SerializerFactory $serializerFactory,
        IngClientConfigurationProviderInterface $ingClientConfiguration
    ) {
        $this->httpClient = $httpClient;
        $this->serializerFactory = $serializerFactory;
        $this->ingClientConfiguration = $ingClientConfiguration;
    }
    public function getClient(string $code): IngApiClient
    {
        $configuration = $this->ingClientConfiguration->getPaymentMethodConfiguration($code);

        return new IngApiClient($this->httpClient, $this->serializerFactory,$configuration);
    }
}
