<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Payment;

interface IngPayPaymentsMethodResolverInterface
{
    public const MIN_TOTAL_5 = 5;

    public const MIN_TOTAL_10 = 10;

    public const MIN_TOTAL_100 = 100;

    public const PLN_CURRENCY = 'PLN';

    public function resolve(): array;
}
