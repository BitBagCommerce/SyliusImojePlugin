<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Payment;

use BitBag\SyliusIngPlugin\Model\Payment\PaymentMethodAndCodeModel;
use BitBag\SyliusIngPlugin\Model\Payment\PaymentMethodAndCodeModelInterface;
use BitBag\SyliusIngPlugin\Resolver\TransactionMethod\TransactionMethodResolverInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class PaymentMethodAndCodeModelFactory
{
    private TransactionMethodResolverInterface $transactionMethodResolver;

    public function __construct(TransactionMethodResolverInterface $transactionMethodResolver)
    {
        $this->transactionMethodResolver = $transactionMethodResolver;
    }

    public function create(PaymentInterface $payment): PaymentMethodAndCodeModelInterface
    {
        $paymentMethod = $this->transactionMethodResolver->resolve($payment);
        $paymentMethodCode = implode($payment->getDetails());

        return new PaymentMethodAndCodeModel($paymentMethod, $paymentMethodCode);
    }
}
