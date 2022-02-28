<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Request;

use BitBag\SyliusIngPlugin\Model\RedirectModelInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface RedirectFactoryInterface
{
    public function create(PaymentInterface $payment): RedirectModelInterface;

    public function createForOneClick(PaymentInterface $payment): RedirectModelInterface;
}
