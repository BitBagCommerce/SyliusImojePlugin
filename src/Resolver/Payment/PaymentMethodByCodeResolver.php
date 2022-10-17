<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Payment;

use BitBag\SyliusIngPlugin\Resolver\TransactionMethod\TransactionMethodResolverInterface;

final class PaymentMethodByCodeResolver implements PaymentMethodByCodeResolverInterface
{
    public function resolve(string $paymentMethodCode): string
    {
        if (\in_array($paymentMethodCode, ['blik', 'card', 'ing'], true)) {
            return $paymentMethodCode;
        }

        if (\in_array($paymentMethodCode, ['imoje_twisto', 'paypo'], true)) {
            return TransactionMethodResolverInterface::PAYMENT_METHOD_PAY_LATER;
        }

        return 'pbl';
    }
}
