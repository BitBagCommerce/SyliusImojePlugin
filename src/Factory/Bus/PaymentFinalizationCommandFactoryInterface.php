<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Bus;

use BitBag\SyliusIngPlugin\Bus\Command\Status\PaymentFinalizationCommandInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface PaymentFinalizationCommandFactoryInterface
{
    public function createNew(string $status, PaymentInterface $payment): PaymentFinalizationCommandInterface;
}
