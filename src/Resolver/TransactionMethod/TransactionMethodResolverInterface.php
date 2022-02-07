<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\TransactionMethod;

use Sylius\Component\Core\Model\PaymentInterface;

interface TransactionMethodResolverInterface
{
    public function resolve(PaymentInterface $payment): string;
}
