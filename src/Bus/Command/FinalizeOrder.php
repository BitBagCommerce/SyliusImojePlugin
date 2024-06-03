<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Command;

use Sylius\Component\Core\Model\OrderInterface;

final class FinalizeOrder
{
    /** @var OrderInterface */
    private $order;

    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }
}
