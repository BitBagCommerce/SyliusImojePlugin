<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\PaymentMethod;

use BitBag\SyliusIngPlugin\Exception\MissingPaymentMethodException;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;

final class PaymentMethodResolver implements PaymentMethodResolverInterface
{
    public function resolve(PaymentInterface $payment): PaymentMethodInterface
    {
        $paymentMethod = $payment->getMethod();

        if ($paymentMethod === null) {
            throw new MissingPaymentMethodException($payment);
        }

        return $paymentMethod;
    }
}
