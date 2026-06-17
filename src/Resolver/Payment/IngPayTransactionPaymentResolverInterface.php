<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Payment;

use Sylius\Component\Core\Model\PaymentInterface;

interface IngPayTransactionPaymentResolverInterface
{
    public function resolve(string $transactionId): PaymentInterface;
}
