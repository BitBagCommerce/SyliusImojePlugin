<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Transaction;

use BitBag\SyliusIngPlugin\Entity\IngTransaction;
use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class IngTransactionFactory implements IngTransactionFactoryInterface
{
    public function create(
        PaymentInterface $payment,
        string $transactionId,
        string $paymentUrl,
        string $serviceId,
        string $orderId,
        string $gatewayCode
    ): IngTransactionInterface {
        return new IngTransaction($transactionId, $payment, $paymentUrl, $serviceId, $orderId, $gatewayCode);
    }
}
