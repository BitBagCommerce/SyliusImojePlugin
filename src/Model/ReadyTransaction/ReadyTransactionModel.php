<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model\ReadyTransaction;

use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class ReadyTransactionModel implements ReadyTransactionModelInterface
{
    private string $status;

    private IngTransactionInterface $ingTransaction;

    private OrderInterface $order;

    public function __construct(
        string $status,
        IngTransactionInterface $ingTransaction,
        OrderInterface $order
    ) {
        $this->status = $status;
        $this->ingTransaction = $ingTransaction;
        $this->order = $order;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getIngTransaction(): IngTransactionInterface
    {
        return $this->ingTransaction;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }
}
