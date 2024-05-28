<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Repository\IngTransaction;

use BitBag\SyliusImojePlugin\Entity\IngTransaction;
use BitBag\SyliusImojePlugin\Entity\IngTransactionInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface IngTransactionRepositoryInterface extends RepositoryInterface
{
    public function getByPaymentId(int $paymentId): ?IngTransaction;

    public function getOneByTransactionId(string $transactionId): IngTransactionInterface;
}
