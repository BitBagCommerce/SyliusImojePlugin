<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Payment;

use BitBag\SyliusIngPayPlugin\Model\Payment\PaymentDataModel;
use BitBag\SyliusIngPayPlugin\Model\Payment\PaymentDataModelInterface;
use BitBag\SyliusIngPayPlugin\Resolver\TransactionMethod\TransactionMethodResolverInterface;
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
