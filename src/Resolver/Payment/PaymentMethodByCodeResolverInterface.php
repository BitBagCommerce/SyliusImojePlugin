<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Payment;

interface PaymentMethodByCodeResolverInterface
{
    public function resolve(string $paymentMethodCode): string;
}
