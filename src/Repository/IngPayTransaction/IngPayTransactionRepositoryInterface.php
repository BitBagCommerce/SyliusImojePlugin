<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Repository\IngPayTransaction;

use BitBag\SyliusIngPayPlugin\Entity\IngPayTransaction;
use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface IngPayTransactionRepositoryInterface extends RepositoryInterface
{
    public function getByPaymentId(int $paymentId): ?IngPayTransaction;

    public function getOneByTransactionId(string $transactionId): IngPayTransactionInterface;
}
