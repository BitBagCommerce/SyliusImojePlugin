<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Payment;

interface IngPaymentsMethodResolverInterface
{
    public function resolve(): ?array;
}
