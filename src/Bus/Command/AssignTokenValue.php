<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Command;

use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Request;

final class AssignTokenValue
{
    private OrderInterface $order;

    private Request $request;

    public function __construct(OrderInterface $order, Request $request)
    {
        $this->order = $order;
        $this->request = $request;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
