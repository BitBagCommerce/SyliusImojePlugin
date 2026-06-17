<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Payment;

interface PaymentMethodByCodeResolverInterface
{
    public function resolve(string $paymentMethodCode): string;
}
