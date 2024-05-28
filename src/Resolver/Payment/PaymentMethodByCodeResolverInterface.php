<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Payment;

interface PaymentMethodByCodeResolverInterface
{
    public function resolve(string $paymentMethodCode): string;
}
