<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Query;

use Sylius\Component\Core\Model\OrderInterface;

final class GetTransactionData
{
    private OrderInterface $order;

    private string $code;

    public function __construct(OrderInterface $order, string $code)
    {
        $this->order = $order;
        $this->code = $code;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
