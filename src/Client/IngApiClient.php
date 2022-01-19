<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Client;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use BitBag\SyliusIngPlugin\Factory\Serializer\SerializerFactory;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

final class IngApiClient implements IngApiClientInterface
{
    private Client $httpClient;

    private SerializerFactory $serializerFactory;

    private IngClientConfigurationInterface $ingClientConfiguration;

    public function __construct(
        Client $httpClient,
        SerializerFactory $serializerFactory,
        IngClientConfigurationInterface $ingClientConfiguration
    ) {
        $this->httpClient = $httpClient;
        $this->serializerFactory = $serializerFactory;
        $this->ingClientConfiguration = $ingClientConfiguration;
    }


    public function createTransaction(
        TransactionModelInterface $transactionModel
    ): ResponseInterface {
        $url = $this->buildUrl();

        $parameters = $this->buildRequestParams($transactionModel);

        try {
            $response = $this->httpClient->post($url, $parameters);
        } catch (GuzzleException $e) {
            throw new IngBadRequestException($e->getMessage());
        }

        return $response;
    }

    private function buildUrl(): string
    {
        return \sprintf('todo');
    }

    private function buildRequestParams(TransactionModelInterface $transactionModel): array
    {
        $request = [];
        $serializer = $this->serializerFactory->createSerializerWithNormalizer();
        $request['body'] = $serializer->serialize($transactionModel, 'json');

        return $request;
    }
}
