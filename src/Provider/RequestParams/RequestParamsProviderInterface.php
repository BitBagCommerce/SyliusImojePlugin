<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Provider\RequestParams;

use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;

interface RequestParamsProviderInterface
{
    public function buildRequestParams(?TransactionModelInterface $transactionModel, string $token): array;
}
