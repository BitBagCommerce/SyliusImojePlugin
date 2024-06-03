<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\PaymentMethod;

use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;

interface PaymentMethodResolverInterface
{
    public function resolve(PaymentInterface $payment): PaymentMethodInterface;
}
