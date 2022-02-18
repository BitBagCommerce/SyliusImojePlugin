<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Command\Status;

use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\PaymentTransitions;

final class MarkAsAuthorized implements PaymentFinalizationCommandInterface
{
    private PaymentInterface $payment;

    public function __construct(PaymentInterface $payment)
    {
        $this->payment = $payment;
    }

    public function getPayment(): PaymentInterface
    {
        return $this->payment;
    }

    public function getPaymentTransitionName(): string
    {
        return PaymentTransitions::TRANSITION_AUTHORIZE;
    }
}
