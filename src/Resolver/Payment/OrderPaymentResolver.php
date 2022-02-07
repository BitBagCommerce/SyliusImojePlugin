<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Payment;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class OrderPaymentResolver implements OrderPaymentResolverInterface
{
    public function resolve(OrderInterface $order): PaymentInterface
    {
        $payment = $order->getLastPayment(PaymentInterface::STATE_NEW);

        if ($payment === null) {
            $payment = $order->getLastPayment(PaymentInterface::STATE_CART);
        }

        if ($payment === null) {
            throw new \InvalidArgumentException(
                sprintf('Order #%d has no payable transaction associated with it.', (int) $order->getId())
            );
        }

        return $payment;
    }
}