<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\PaymentMethod;

use BitBag\SyliusImojePlugin\Exception\MissingPaymentMethodException;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;

final class PaymentMethodResolver implements PaymentMethodResolverInterface
{
    public function resolve(PaymentInterface $payment): PaymentMethodInterface
    {
        $paymentMethod = $payment->getMethod();

        if (null === $paymentMethod) {
            throw new MissingPaymentMethodException($payment);
        }

        return $paymentMethod;
    }
}
