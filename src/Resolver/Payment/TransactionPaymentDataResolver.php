<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Payment;

use BitBag\SyliusIngPlugin\Factory\Payment\PaymentDataModelFactoryInterface;
use BitBag\SyliusIngPlugin\Model\Payment\PaymentDataModel;
use BitBag\SyliusIngPlugin\Model\Payment\PaymentDataModelInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class TransactionPaymentDataResolver implements TransactionPaymentDataResolverInterface
{
    private PaymentDataModelFactoryInterface $paymentDataModelFactory;

    private PaymentMethodByCodeResolver $paymentMethodByCodeResolver;

    public function __construct(
        PaymentDataModelFactoryInterface $paymentDataModelFactory,
        PaymentMethodByCodeResolver $paymentMethodByCodeResolver
    ) {
        $this->paymentDataModelFactory = $paymentDataModelFactory;
        $this->paymentMethodByCodeResolver = $paymentMethodByCodeResolver;
    }

    public function resolve(
        ?string $paymentMethodCode,
        PaymentInterface $payment,
        ?string $blikCode
    ): PaymentDataModelInterface {
        if (null === $blikCode) {
            $isBlik = 'blik' === implode($payment->getDetails());
        } else {
            $isBlik = true;
        }

        if (null === $paymentMethodCode) {
            return $this->paymentDataModelFactory->create($payment, $isBlik);
        }
        $paymentMethod = $this->paymentMethodByCodeResolver->resolve($paymentMethodCode);

        return new PaymentDataModel($paymentMethod, $paymentMethodCode);
    }
}
