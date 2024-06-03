<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\TransactionMethod;

use Sylius\Component\Core\Model\PaymentInterface;

final class TransactionMethodResolver implements TransactionMethodResolverInterface
{
    public function resolve(PaymentInterface $payment): string
    {
        $paymentDetails = implode($payment->getDetails());

        if ('blik' === $paymentDetails) {
            return self::PAYMENT_METHOD_BLIK;
        }

        if ('card' === $paymentDetails) {
            return self::PAYMENT_METHOD_CARD;
        }

        if ('ing' === $paymentDetails) {
            return self::PAYMENT_METHOD_ING;
        }

        if ('imoje_twisto' === $paymentDetails || 'paypo' === $paymentDetails || 'pragma_go' === $paymentDetails) {
            return self::PAYMENT_METHOD_PAY_LATER;
        }

        return self::PAYMENT_METHOD_PBL;
    }
}
