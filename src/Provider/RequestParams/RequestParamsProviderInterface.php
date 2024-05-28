<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider\RequestParams;

use BitBag\SyliusImojePlugin\Model\TransactionModelInterface;

interface RequestParamsProviderInterface
{
    public function buildRequestParams(TransactionModelInterface $transactionModel, string $token): array;

    public function buildAuthorizeRequest(string $token): array;

    public function buildRequestRefundParams(
        string $token,
        string $serviceId,
        int $amount
    ): array;
}
