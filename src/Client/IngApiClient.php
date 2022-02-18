<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Client;

use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
use BitBag\SyliusIngPlugin\Provider\RequestParams\RequestParamsProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

final class IngApiClient implements IngApiClientInterface
{
    private Client $httpClient;

    private RequestParamsProviderInterface $requestParamsProvider;

    private string $token;

    private string $url;

    public function __construct(
        Client $httpClient,
        RequestParamsProviderInterface $requestParamsProvider,
        string $token,
        string $url
    ) {
        $this->httpClient = $httpClient;
        $this->requestParamsProvider = $requestParamsProvider;
        $this->token = $token;
        $this->url = $url;
    }

    public function createTransaction(
        TransactionModelInterface $transactionModel
    ): ResponseInterface {
        $url = \sprintf('%s%s', $this->url, self::TRANSACTION_ENDPOINT);

        $parameters = $this->requestParamsProvider->buildRequestParams($transactionModel, $this->token);

        try {
            $response = $this->httpClient->post($url, $parameters);
        } catch (GuzzleException $e) {
            throw new IngBadRequestException($e->getMessage());
        }

        return $response;
    }

    public function getTransactionData(string $url): ResponseInterface
    {
        $parameters = $this->requestParamsProvider->buildAuthorizeRequest($this->token);

        try {
            $response = $this->httpClient->get($url, $parameters);
        } catch (GuzzleException $e) {
            throw new IngBadRequestException($e->getMessage());
        }

        return $response;
    }
}
