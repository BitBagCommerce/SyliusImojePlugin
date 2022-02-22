<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Repository\IngTransaction;

use BitBag\SyliusIngPlugin\Entity\IngTransaction;
use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface IngTransactionRepositoryInterface extends RepositoryInterface
{
    public function getByPaymentId(int $paymentId): ?IngTransaction;

    public function getOneByTransactionId(string $transactionId): IngTransactionInterface;
}
