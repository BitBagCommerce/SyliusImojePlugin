<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model\ReadyTransaction;

use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;

interface ReadyTransactionModelInterface
{
    public function getStatus(): string;

    public function getIngTransaction(): IngTransactionInterface;
}
