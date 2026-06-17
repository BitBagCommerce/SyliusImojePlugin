<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Transaction;

use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface IngPayTransactionFactoryInterface
{
    public function create(
        PaymentInterface $payment,
        string $transactionId,
        ?string $paymentUrl,
        string $serviceId,
        string $orderId,
        string $gatewayCode,
    ): IngPayTransactionInterface;
}
