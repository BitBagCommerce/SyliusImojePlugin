<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Model\ReadyTransaction;

use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface ReadyTransactionModelInterface
{
    public function getStatus(): string;

    public function getingPayTransaction(): IngPayTransactionInterface;

    public function getOrder(): OrderInterface;
}
