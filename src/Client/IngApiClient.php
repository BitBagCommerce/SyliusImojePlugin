<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Client;

use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use BitBag\SyliusIngPlugin\Factory\Serializer\SerializerFactoryInterface;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

final class IngApiClient implements IngApiClientInterface
{
    private Client $httpClient;

    private SerializerFactoryInterface $serializerFactory;

    private string $token;

    private string $url;

    public function __construct(Client $httpClient, SerializerFactoryInterface $serializerFactory, string $token, string $url)
    {
        $this->httpClient = $httpClient;
        $this->serializerFactory = $serializerFactory;
        $this->token = $token;
        $this->url = $url;
    }

    public function createTransaction(
        TransactionModelInterface $transactionModel
    ): ResponseInterface {
        $url = \sprintf('%s%s', $this->url, self::TRANSACTION_ENDPOINT);

        $parameters = $this->buildRequestParams($transactionModel, $this->token);

        try {
            $response = $this->httpClient->post($url, $parameters);
        } catch (GuzzleException $e) {
            throw new IngBadRequestException($e->getMessage());
        }

        return $response;
    }

    public function gettingTransactionData(string $url, string $token): ResponseInterface
    {
        $parameters = $this->buildRequestParams(null, $token);

        try {
            $response = $this->httpClient->get($url, $parameters);
        } catch (GuzzleException $e) {
            throw new IngBadRequestException($e->getMessage());
        }

        return $response;
    }

    private function buildRequestParams(
        ?TransactionModelInterface $transactionModel,
        string $token
    ): array {
        $request = [];
        $serializer = $this->serializerFactory->createSerializerWithNormalizer();

        if ($transactionModel !== null) {
            $request['body'] = $serializer->serialize($transactionModel, 'json');
        }

        $request['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ];

        return $request;
    }
}
