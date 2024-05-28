<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Payment;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class OrderPaymentResolver implements OrderPaymentResolverInterface
{
    public function resolve(OrderInterface $order): PaymentInterface
    {
        $payment = $order->getLastPayment();
        if (null === $payment) {
            $payment = $order->getLastPayment();
        }

        if (null === $payment) {
            throw new \InvalidArgumentException(
                \sprintf('Order #%d has no payable transaction associated with it.', (int) $order->getId())
            );
        }

        return $payment;
    }
}
