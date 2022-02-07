<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Payment;

use BitBag\SyliusIngPlugin\Model\Payment\PaymentMethodAndCodeModelInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface PaymentMethodAndCodeModelFactoryInterface
{
    public function create(PaymentInterface $payment): PaymentMethodAndCodeModelInterface;
}
