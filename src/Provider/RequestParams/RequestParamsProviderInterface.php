<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Provider\RequestParams;

use BitBag\SyliusIngPayPlugin\Model\TransactionModelInterface;

interface RequestParamsProviderInterface
{
    public function buildRequestParams(TransactionModelInterface $transactionModel, string $token): array;

    public function buildAuthorizeRequest(string $token): array;

    public function buildRequestRefundParams(
        string $token,
        string $serviceId,
        int $amount,
    ): array;
}
