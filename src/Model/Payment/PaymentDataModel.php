<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model\Payment;

final class PaymentDataModel implements PaymentDataModelInterface
{
    private string $paymentMethod;

    private string $paymentMethodCode;

    public function __construct(string $paymentMethod, string $paymentMethodCode)
    {
        $this->paymentMethod = $paymentMethod;
        $this->paymentMethodCode = $paymentMethodCode;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getPaymentMethodCode(): string
    {
        return $this->paymentMethodCode;
    }
}
