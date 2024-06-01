<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Transaction;

use BitBag\SyliusImojePlugin\Entity\ImojeTransaction;
use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class ImojeTransactionFactory implements ImojeTransactionFactoryInterface
{
    public function create(
        PaymentInterface $payment,
        string $transactionId,
        ?string $paymentUrl,
        string $serviceId,
        string $orderId,
        string $gatewayCode
    ): ImojeTransactionInterface {
        return new ImojeTransaction($transactionId, $payment, $paymentUrl, $serviceId, $orderId, $gatewayCode);
    }
}
