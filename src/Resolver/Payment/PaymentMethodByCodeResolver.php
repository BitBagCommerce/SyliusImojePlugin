<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Payment;

use BitBag\SyliusIngPayPlugin\Resolver\TransactionMethod\TransactionMethodResolverInterface;

final class PaymentMethodByCodeResolver implements PaymentMethodByCodeResolverInterface
{
    public function resolve(string $paymentMethodCode): string
    {
        if (\in_array($paymentMethodCode, ['blik', 'card', 'ing'], true)) {
            return $paymentMethodCode;
        }

        if (\in_array($paymentMethodCode, ['ing_pay_twisto', 'paypo', 'pragma_go'], true)) {
            return TransactionMethodResolverInterface::PAYMENT_METHOD_PAY_LATER;
        }

        return 'pbl';
    }
}
