<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Payment;

final class PaymentMethodByCodeResolver implements PaymentMethodByCodeResolverInterface
{
    public function resolve(string $paymentMethodCode): string
    {
        if (\in_array($paymentMethodCode, ['blik', 'card', 'ing'], true)) {
            return $paymentMethodCode;
        }

        return 'pbl';
    }
}
