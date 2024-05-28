<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Transaction;

use BitBag\SyliusImojePlugin\Entity\IngTransaction;
use BitBag\SyliusImojePlugin\Entity\IngTransactionInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class IngTransactionFactory implements IngTransactionFactoryInterface
{
    public function create(
        PaymentInterface $payment,
        string $transactionId,
        ?string $paymentUrl,
        string $serviceId,
        string $orderId,
        string $gatewayCode
    ): IngTransactionInterface {
        return new IngTransaction($transactionId, $payment, $paymentUrl, $serviceId, $orderId, $gatewayCode);
    }
}
