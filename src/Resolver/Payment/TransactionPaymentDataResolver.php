<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Payment;

use BitBag\SyliusIngPayPlugin\Factory\Payment\PaymentDataModelFactoryInterface;
use BitBag\SyliusIngPayPlugin\Model\Payment\PaymentDataModel;
use BitBag\SyliusIngPayPlugin\Model\Payment\PaymentDataModelInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class TransactionPaymentDataResolver implements TransactionPaymentDataResolverInterface
{
    private PaymentDataModelFactoryInterface $paymentDataModelFactory;

    private PaymentMethodByCodeResolver $paymentMethodByCodeResolver;

    public function __construct(
        PaymentDataModelFactoryInterface $paymentDataModelFactory,
        PaymentMethodByCodeResolver $paymentMethodByCodeResolver,
    ) {
        $this->paymentDataModelFactory = $paymentDataModelFactory;
        $this->paymentMethodByCodeResolver = $paymentMethodByCodeResolver;
    }

    public function resolve(
        ?string $paymentMethodCode,
        PaymentInterface $payment,
        ?string $blikCode,
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
