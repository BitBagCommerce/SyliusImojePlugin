<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Command\Status;

use Sylius\Component\Core\Model\PaymentInterface;

interface PaymentFinalizationCommandInterface
{
    public function getPayment(): PaymentInterface;

    public function getPaymentTransitionName(): string;
}
