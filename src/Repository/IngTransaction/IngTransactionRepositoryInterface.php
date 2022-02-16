<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Repository\IngTransaction;

use BitBag\SyliusIngPlugin\Entity\IngTransaction;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface IngTransactionRepositoryInterface extends RepositoryInterface
{
    public function findByPaymentId(int $paymentId): ?IngTransaction;
}
