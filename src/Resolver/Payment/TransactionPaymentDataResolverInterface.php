<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Payment;

use BitBag\SyliusIngPlugin\Model\Payment\PaymentDataModelInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface TransactionPaymentDataResolverInterface
{
    public function resolve(
        ?string $paymentMethodCode,
        PaymentInterface $payment,
        ?string $blikCode
    ): PaymentDataModelInterface;
}
