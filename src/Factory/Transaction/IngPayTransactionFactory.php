<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Transaction;

use BitBag\SyliusIngPayPlugin\Entity\IngPayTransaction;
use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class IngPayTransactionFactory implements IngPayTransactionFactoryInterface
{
    public function create(
        PaymentInterface $payment,
        string $transactionId,
        ?string $paymentUrl,
        string $serviceId,
        string $orderId,
        string $gatewayCode,
    ): IngPayTransactionInterface {
        return new IngPayTransaction($transactionId, $payment, $paymentUrl, $serviceId, $orderId, $gatewayCode);
    }
}
