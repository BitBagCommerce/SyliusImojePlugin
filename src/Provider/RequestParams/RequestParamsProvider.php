<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider\RequestParams;

use BitBag\SyliusImojePlugin\Factory\Refund\RefundModelFactoryInterface;
use BitBag\SyliusImojePlugin\Factory\Serializer\SerializerFactoryInterface;
use BitBag\SyliusImojePlugin\Model\TransactionModelInterface;

final class RequestParamsProvider implements RequestParamsProviderInterface
{
    private SerializerFactoryInterface $serializerFactory;

    private RefundModelFactoryInterface $refundModelFactory;

    public function __construct(
        SerializerFactoryInterface $serializerFactory,
        RefundModelFactoryInterface $refundModelFactory
    ) {
        $this->serializerFactory = $serializerFactory;
        $this->refundModelFactory = $refundModelFactory;
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

    public function buildRequestRefundParams(
        string $token,
        string $serviceId,
        int $amount
    ): array {
        $serializer = $this->serializerFactory->createSerializerWithNormalizer();
        $request = $this->addAuthorizationHeaders($token);
        $refundModel = $this->refundModelFactory->create('refund', $serviceId, $amount);
        $request['body'] = $serializer->serialize($refundModel, 'json');

        return $request;
    }

    private function addAuthorizationHeaders(string $token): array
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
