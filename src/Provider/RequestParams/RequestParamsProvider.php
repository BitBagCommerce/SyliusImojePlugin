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
        $serializer = $this->serializerFactory->createSerializerWithNormalizer();
        $request = $this->addAuthorizationHeaders($token);
        $request['body'] = $serializer->serialize($transactionModel, 'json');

        return $request;
    }

    public function buildAuthorizeRequest(string $token): array
    {
        return $this->addAuthorizationHeaders($token);
    }

    private function addAuthorizationHeaders(string $token)
    {
        $request = [];

        $request['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ];

        return $request;
    }
}
