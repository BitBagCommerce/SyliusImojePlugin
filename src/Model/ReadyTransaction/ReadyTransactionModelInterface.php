<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\ReadyTransaction;

use BitBag\SyliusImojePlugin\Entity\IngTransactionInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface ReadyTransactionModelInterface
{
    public function getStatus(): string;

    public function getIngTransaction(): IngTransactionInterface;

    public function getOrder(): OrderInterface;
}
