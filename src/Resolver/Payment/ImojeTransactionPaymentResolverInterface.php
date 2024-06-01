<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Payment;

use Sylius\Component\Core\Model\PaymentInterface;

interface ImojeTransactionPaymentResolverInterface
{
    public function resolve(string $transactionId): PaymentInterface;
}
