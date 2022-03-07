<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Client;

use BitBag\SyliusIngPlugin\Model\PaymentMethod\ServiceModelInterface;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
use Psr\Http\Message\ResponseInterface;

interface IngApiClientInterface
{
    public const TRANSACTION_ENDPOINT = 'transaction';

    public function createTransaction(
        TransactionModelInterface $transactionModel
    ): ResponseInterface;

    public function getTransactionData(string $url): ResponseInterface;

    public function getShopInfo(string $serviceId): ServiceModelInterface;

    public function refundTransaction(
        string $url,
        string $serviceId,
        int $amount
    ): ResponseInterface;
}
