<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Payment;

use BitBag\SyliusIngPlugin\Model\Payment\PaymentDataModelInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface PaymentDataModelFactoryInterface
{
    public function create(PaymentInterface $payment): PaymentDataModelInterface;
}
