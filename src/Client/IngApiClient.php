<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Client;

use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class IngApiClient implements IngApiClientInterface
{
    private ClientInterface $httpClient;

    private SerializerInterface $serializer;

    private string $baseUrl;

    public function __construct(ClientInterface $httpClient, SerializerInterface $serializer, string $baseUrl)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->baseUrl = $baseUrl;
    }

    public function createRequest(
        TransactionModelInterface $createTransactionModel,
        string $action
    ): ?ResponseInterface {
        $url = $this->buildUrl($action);

        $parameters = $this->buildRequestParams($createTransactionModel);

        try {
            $response = $this->httpClient->request(self::POST_METHOD, $url, $parameters);
        } catch (GuzzleException $e) {
            return null;
        }

        return $response;
    }

    private function buildUrl(string $action): string
    {
        return sprintf('%s/%s', $this->baseUrl, $action);
    }

    private function buildRequestParams(TransactionModelInterface $createTransactionModel): array
    {
        $request = [];

        $request['body'] = $this->serializer->serialize($createTransactionModel, 'json');

        return $request;
    }
}
