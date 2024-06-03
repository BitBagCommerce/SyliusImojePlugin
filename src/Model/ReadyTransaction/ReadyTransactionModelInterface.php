<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\ReadyTransaction;

use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface ReadyTransactionModelInterface
{
    public function getStatus(): string;

    public function getimojeTransaction(): ImojeTransactionInterface;

    public function getOrder(): OrderInterface;
}
