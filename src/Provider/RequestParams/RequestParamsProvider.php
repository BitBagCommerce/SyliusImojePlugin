<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Provider\RequestParams;

use BitBag\SyliusIngPlugin\Factory\Serializer\SerializerFactoryInterface;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;

final class RequestParamsProvider implements RequestParamsProviderInterface
{
    private SerializerFactoryInterface $serializerFactory;

    public function __construct(SerializerFactoryInterface $serializerFactory)
    {
        $this->serializerFactory = $serializerFactory;
    }

    public function buildRequestParams(TransactionModelInterface $transactionModel, string $token): array
    {
        $request = [];
        $serializer = $this->serializerFactory->createSerializerWithNormalizer();

        $request['body'] = $serializer->serialize($transactionModel, 'json');
        $request['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ];

        return $request;
    }

    public function buildAuthorizeRequest(string $token): array
    {
        $request['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ];

        return $request;
    }
}
