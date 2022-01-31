<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Command;

use Sylius\Component\Core\Model\PaymentInterface;

final class TakeOverPayment
{
    /** @var PaymentInterface */
    private $payment;

    /** @var string */
    private $paymentCode;

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
