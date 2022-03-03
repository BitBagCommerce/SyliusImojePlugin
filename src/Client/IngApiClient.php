<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Client;

use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use BitBag\SyliusIngPlugin\Model\PaymentMethod\ServiceModel;
use BitBag\SyliusIngPlugin\Model\PaymentMethod\ServiceModelInterface;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
use BitBag\SyliusIngPlugin\Provider\RequestParams\RequestParamsProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Serializer;

final class IngApiClient implements IngApiClientInterface
{
    private Client $httpClient;

    private RequestParamsProviderInterface $requestParamsProvider;

    private string $token;

    private string $url;

    private Serializer $serializer;

    public function __construct(
        Client $httpClient,
        RequestParamsProviderInterface $requestParamsProvider,
        Serializer $serializer,
        string $token,
        string $url
    ) {
        $this->httpClient = $httpClient;
        $this->requestParamsProvider = $requestParamsProvider;
        $this->token = $token;
        $this->url = $url;
        $this->serializer = $serializer;
    }

    public function createTransaction(
        TransactionModelInterface $transactionModel
    ): ResponseInterface {
        $url = $this->url . self::TRANSACTION_ENDPOINT;

        $parameters = $this->requestParamsProvider->buildRequestParams($transactionModel, $this->token);

        try {
            $response = $this->httpClient->post($url, $parameters);
        } catch (GuzzleException $e) {
            throw new IngBadRequestException($e->getMessage());
        }

        return $response;
    }

    public function getShopInfo(string $serviceId): ServiceModelInterface
    {
//        $url = \sprintf('%sservice/%s', $this->url, $serviceId);
        $url = $this->url . 'service/' . $serviceId;
        $parameters = $this->requestParamsProvider->buildAuthorizeRequest($this->token);

        try {
            $response = $this->httpClient->get($url, $parameters);
        } catch (GuzzleException $e) {
            throw new IngBadRequestException($e->getMessage());
        }

        $json = \json_decode($response->getBody()->getContents(), true);
        $servicePayload = $json['service'] ?? [];
        $result = $this->serializer->denormalize($servicePayload, ServiceModel::class, 'json');

        return $result;
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
