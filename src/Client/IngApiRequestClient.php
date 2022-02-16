<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Client;

use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

final class IngApiRequestClient implements IngApiRequestClientInterface
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function gettingTransactionData(string $url, string $token): ResponseInterface
    {
        $parameters = $this->buildRequestParams($token);

        try {
            $response = $this->httpClient->get($url, $parameters);
        } catch (GuzzleException $e) {
            throw new IngBadRequestException($e->getMessage());
        }

        return $response;
    }

    private function buildRequestParams(
        string $token
    ): array {
        $request = [];
        $request['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ];

        return $request;
    }
}
