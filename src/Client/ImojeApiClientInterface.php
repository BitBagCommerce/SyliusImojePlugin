<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Client;

use BitBag\SyliusImojePlugin\Model\PaymentMethod\ServiceModelInterface;
use BitBag\SyliusImojePlugin\Model\TransactionModelInterface;
use Psr\Http\Message\ResponseInterface;

interface ImojeApiClientInterface
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
