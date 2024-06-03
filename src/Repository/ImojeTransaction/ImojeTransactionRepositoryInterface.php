<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Repository\ImojeTransaction;

use BitBag\SyliusImojePlugin\Entity\ImojeTransaction;
use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface ImojeTransactionRepositoryInterface extends RepositoryInterface
{
    public function getByPaymentId(int $paymentId): ?ImojeTransaction;

    public function getOneByTransactionId(string $transactionId): ImojeTransactionInterface;
}
