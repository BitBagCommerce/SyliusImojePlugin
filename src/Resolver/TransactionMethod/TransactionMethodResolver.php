<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\TransactionMethod;

use Sylius\Component\Core\Model\PaymentInterface;

final class TransactionMethodResolver implements TransactionMethodResolverInterface
{
    public function resolve(PaymentInterface $payment): string
    {
        $paymentDetails = implode($payment->getDetails());

        if ($paymentDetails === 'blik') {
            return self::PAYMENT_METHOD_BLIK;
        }

        if ($paymentDetails === 'card') {
            return self::PAYMENT_METHOD_CARD;
        }

        if ($paymentDetails === 'ing') {
            return self::PAYMENT_METHOD_ING;
        }

        return self::PAYMENT_METHOD_PBL;
    }
}
