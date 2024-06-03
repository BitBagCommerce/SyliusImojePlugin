<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Command;

use Sylius\Component\Core\Model\PaymentInterface;

final class TakeOverPayment
{
    private PaymentInterface $payment;

    private string $paymentCode;

    public function __construct(PaymentInterface $payment, string $paymentCode)
    {
        $this->paymentCode = $paymentCode;
        $this->payment = $payment;
    }

    public function getPayment(): PaymentInterface
    {
        return $this->payment;
    }

    public function getPaymentCode(): string
    {
        return $this->paymentCode;
    }
}
