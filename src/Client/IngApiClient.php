<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Client;

use BitBag\SyliusIngPlugin\Form\Type\BadRequestException;
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

    public function createTransaction(
        TransactionModelInterface $transactionModel,
        string $action
    ): ResponseInterface {
        $url = $this->buildUrl($action);

        $parameters = $this->buildRequestParams($transactionModel);

        try {
            $response = $this->httpClient->post($url, $parameters);
        } catch (GuzzleException $e) {
            $badRequest = new BadRequestException('Bad request');

            return $badRequest->getCode();
        }

        return $response;
    }

    private function buildUrl(string $action): string
    {
        return \sprintf('%s/%s', $this->baseUrl, $action);
    }

    private function buildRequestParams(TransactionModelInterface $transactionModel): array
    {
        $request = [];

        $request['body'] = $this->serializer->serialize($transactionModel, 'json');

        return $request;
    }
}
