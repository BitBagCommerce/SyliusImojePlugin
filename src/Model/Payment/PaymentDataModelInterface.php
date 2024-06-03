<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\Payment;

interface PaymentDataModelInterface
{
    public function getPaymentMethod(): string;

    public function getPaymentMethodCode(): string;
}
