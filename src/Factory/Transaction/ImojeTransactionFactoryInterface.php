<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Transaction;

use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface ImojeTransactionFactoryInterface
{
    public function create(
        PaymentInterface $payment,
        string $transactionId,
        ?string $paymentUrl,
        string $serviceId,
        string $orderId,
        string $gatewayCode,
    ): ImojeTransactionInterface;
}
