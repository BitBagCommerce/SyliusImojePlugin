<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Client;

use BitBag\SyliusImojePlugin\Exception\ImojeBadRequestException;
use BitBag\SyliusImojePlugin\Model\PaymentMethod\ServiceModel;
use BitBag\SyliusImojePlugin\Model\PaymentMethod\ServiceModelInterface;
use BitBag\SyliusImojePlugin\Model\TransactionModelInterface;
use BitBag\SyliusImojePlugin\Provider\RequestParams\RequestParamsProviderInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Symfony\Component\Serializer\Serializer;

final class ImojeApiClient implements ImojeApiClientInterface
{
    private ClientInterface $httpClient;

    private RequestParamsProviderInterface $requestParamsProvider;

    private string $token;

    private string $url;

    private Serializer $serializer;

    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    public function __construct(
        ClientInterface $httpClient,
        RequestParamsProviderInterface $requestParamsProvider,
        Serializer $serializer,
        string $token,
        string $url,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->httpClient = $httpClient;
        $this->requestParamsProvider = $requestParamsProvider;
        $this->token = $token;
        $this->url = $url;
        $this->serializer = $serializer;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    public function createTransaction(
        TransactionModelInterface $transactionModel
    ): ResponseInterface {
        $url = $this->url . self::TRANSACTION_ENDPOINT;
        $parameters = $this->requestParamsProvider->buildRequestParams($transactionModel, $this->token);
        try {
            $request = $this->requestFactory
                ->createRequest('POST', $url)
                ->withHeader('Accept', 'application/json')
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Authorization', \sprintf('Bearer %s', $this->token))
                ->withBody($this->streamFactory->createStream($parameters['body']));

            $response = $this->httpClient->sendRequest($request);

        } catch (ClientExceptionInterface $e) {
            throw new ImojeBadRequestException($e->getMessage());
        }

        return $response;
    }

    public function getShopInfo(string $serviceId): ServiceModelInterface
    {
        $url = $this->url . 'service/' . $serviceId;

        try {
            $request = $this->requestFactory->createRequest('GET', $url)
                ->withHeader('Accept', 'application/json')
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Authorization', \sprintf('Bearer %s', $this->token));
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new ImojeBadRequestException($e->getMessage());
        }

        $json = \json_decode($response->getBody()->getContents(), true);
        $servicePayload = $json['service'] ?? [];
        $result = $this->serializer->denormalize($servicePayload, ServiceModel::class, 'json');

        return $result;
    }

    public function getTransactionData(string $url): ResponseInterface
    {
        try {
            $request = $this->requestFactory->createRequest('GET', $url)
                ->withHeader('Accept', 'application/json')
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Authorization', \sprintf('Bearer %s', $this->token));

            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new ImojeBadRequestException($e->getMessage());
        }

        return $response;
    }

    public function refundTransaction(
        string $url,
        string $serviceId,
        int $amount
    ): ResponseInterface {
        $parameters = $this->requestParamsProvider->buildRequestRefundParams($this->token, $serviceId, $amount);
        try {
            $request = $this->requestFactory
                ->createRequest('POST', $url)
                ->withHeader('Accept', 'application/json')
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Authorization', \sprintf('Bearer %s', $this->token))
                ->withBody($this->streamFactory->createStream($parameters['body']));

            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new ImojeBadRequestException($e->getMessage());
        }

        return $response;
    }
}
