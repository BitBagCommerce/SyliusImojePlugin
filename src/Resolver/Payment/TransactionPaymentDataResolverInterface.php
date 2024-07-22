<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Payment;

use BitBag\SyliusImojePlugin\Model\Payment\PaymentDataModelInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface TransactionPaymentDataResolverInterface
{
    public function resolve(
        ?string $paymentMethodCode,
        PaymentInterface $payment,
        ?string $blikCode,
    ): PaymentDataModelInterface;
}
