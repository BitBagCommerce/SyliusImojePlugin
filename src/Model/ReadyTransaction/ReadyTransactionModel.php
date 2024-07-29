<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\ReadyTransaction;

use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class ReadyTransactionModel implements ReadyTransactionModelInterface
{
    private string $status;

    private ImojeTransactionInterface $imojeTransaction;

    private OrderInterface $order;

    public function __construct(
        string $status,
        ImojeTransactionInterface $imojeTransaction,
        OrderInterface $order,
    ) {
        $this->status = $status;
        $this->imojeTransaction = $imojeTransaction;
        $this->order = $order;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getimojeTransaction(): ImojeTransactionInterface
    {
        return $this->imojeTransaction;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }
}
