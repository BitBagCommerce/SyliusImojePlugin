<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Client;

use BitBag\SyliusIngPlugin\Client\IngApiClient;
use BitBag\SyliusIngPlugin\Factory\Serializer\SerializerFactory;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Resolver\UrlResolver\IngUrlResolver;
use GuzzleHttp\Client;

final class IngClientFactory implements IngClientFactoryInterface
{
    private Client $httpClient;

    private SerializerFactory $serializerFactory;

    private IngClientConfigurationProviderInterface $clientConfigurationProvider;

    private IngUrlResolver $ingUrlResolver;

    public function __construct(
        Client $httpClient,
        SerializerFactory $serializerFactory,
        IngClientConfigurationProviderInterface $clientConfigurationProvider,
        IngUrlResolver $ingUrlResolver
    ) {
        $this->httpClient = $httpClient;
        $this->serializerFactory = $serializerFactory;
        $this->clientConfigurationProvider = $clientConfigurationProvider;
        $this->ingUrlResolver = $ingUrlResolver;
    }

    public function getClient(string $code): IngApiClient
    {
        return new IngApiClient(
            $this->httpClient,
            $this->serializerFactory,
            $this->clientConfigurationProvider,
            $this->ingUrlResolver
        );
    }
}
