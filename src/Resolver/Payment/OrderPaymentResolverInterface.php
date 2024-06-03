<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Payment;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface OrderPaymentResolverInterface
{
    public function resolve(OrderInterface $order): PaymentInterface;
}
