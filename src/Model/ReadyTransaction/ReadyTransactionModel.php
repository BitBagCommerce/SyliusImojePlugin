<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Model\ReadyTransaction;

use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class ReadyTransactionModel implements ReadyTransactionModelInterface
{
    private string $status;

    private IngPayTransactionInterface $ingPayTransaction;

    private OrderInterface $order;

    public function __construct(
        string $status,
        IngPayTransactionInterface $ingPayTransaction,
        OrderInterface $order,
    ) {
        $this->status = $status;
        $this->ingPayTransaction = $ingPayTransaction;
        $this->order = $order;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getingPayTransaction(): IngPayTransactionInterface
    {
        return $this->ingPayTransaction;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }
}
