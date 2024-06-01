<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\ReadyTransaction;

use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class ReadyTransactionModel implements ReadyTransactionModelInterface
{
    private string $status;

    private ImojeTransactionInterface $ingTransaction;

    private OrderInterface $order;

    public function __construct(
        string                    $status,
        ImojeTransactionInterface $ingTransaction,
        OrderInterface            $order
    ) {
        $this->status = $status;
        $this->ingTransaction = $ingTransaction;
        $this->order = $order;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getIngTransaction(): ImojeTransactionInterface
    {
        return $this->ingTransaction;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }
}
