<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model\Payment;

interface PaymentMethodAndCodeModelInterface
{
    public function getPaymentMethod(): string;

    public function getPaymentMethodCode(): string;
}
