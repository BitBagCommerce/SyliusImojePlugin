<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Client;

use BitBag\SyliusIngPayPlugin\Model\PaymentMethod\ServiceModelInterface;
use BitBag\SyliusIngPayPlugin\Model\TransactionModelInterface;
use Psr\Http\Message\ResponseInterface;

interface IngPayApiClientInterface
{
    public const TRANSACTION_ENDPOINT = 'transaction';

    public function createTransaction(
        TransactionModelInterface $transactionModel,
    ): ResponseInterface;

    public function getTransactionData(string $url): ResponseInterface;

    public function getShopInfo(string $serviceId): ServiceModelInterface;

    public function refundTransaction(
        string $url,
        string $serviceId,
        int $amount,
    ): ResponseInterface;
}
