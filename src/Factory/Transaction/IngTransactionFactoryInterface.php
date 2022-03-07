<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Transaction;

use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface IngTransactionFactoryInterface
{
    public function create(
        PaymentInterface $payment,
        string $transactionId,
        ?string $paymentUrl,
        string $serviceId,
        string $orderId,
        string $gatewayCode
    ): IngTransactionInterface;
}
