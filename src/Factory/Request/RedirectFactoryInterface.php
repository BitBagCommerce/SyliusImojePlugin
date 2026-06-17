<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Request;

use BitBag\SyliusIngPayPlugin\Model\RedirectModelInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface RedirectFactoryInterface
{
    public function create(PaymentInterface $payment): RedirectModelInterface;

    public function createForOneClick(PaymentInterface $payment): RedirectModelInterface;
}
