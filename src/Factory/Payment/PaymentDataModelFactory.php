<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Payment;

use BitBag\SyliusIngPlugin\Model\Payment\PaymentDataModel;
use BitBag\SyliusIngPlugin\Model\Payment\PaymentDataModelInterface;
use BitBag\SyliusIngPlugin\Resolver\TransactionMethod\TransactionMethodResolverInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class PaymentDataModelFactory implements PaymentDataModelFactoryInterface
{
    private TransactionMethodResolverInterface $transactionMethodResolver;

    public function __construct(TransactionMethodResolverInterface $transactionMethodResolver)
    {
        $this->transactionMethodResolver = $transactionMethodResolver;
    }

    public function create(PaymentInterface $payment, bool $isBlik): PaymentDataModelInterface
    {
        if ($isBlik) {
            return new PaymentDataModel('blik', 'blik');
        }

        $paymentMethod = $this->transactionMethodResolver->resolve($payment);
        $paymentMethodCode = implode($payment->getDetails());

        return new PaymentDataModel($paymentMethod, $paymentMethodCode);
    }
}
