<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Client;

use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
use BitBag\SyliusIngPlugin\Serializer\SerializerFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class IngApiClient implements IngApiClientInterface
{
    private Client $httpClient;

    private SerializerFactory $serializerFactory;

    private string $baseUrl;

    public function __construct(Client $httpClient, SerializerFactory $serializerFactory, string $baseUrl)
    {
        $this->httpClient = $httpClient;
        $this->serializerFactory = $serializerFactory;
        $this->baseUrl = $baseUrl;
    }

    public function createTransaction(
        TransactionModelInterface $transactionModel
    ): ResponseInterface {
        $url = $this->buildUrl();

        $parameters = $this->buildRequestParams($transactionModel);

        try {
            $response = $this->httpClient->post($url,$parameters);
        } catch (GuzzleException $e) {
            throw new IngBadRequestException($e->getMessage());
        }

        return $response;
    }

    private function buildUrl(): string
    {
        return \sprintf('%s/', $this->baseUrl);
    }

    private function buildRequestParams(TransactionModelInterface $transactionModel): array
    {
        $request = [];
        $serializer = $this->serializerFactory->createSerializerWithNormalizer();
        $request['body'] = $serializer->serialize($transactionModel, 'json');

        return $request;
    }
}
