<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Transaction;

use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class IngTransactionFactory implements IngTransactionFactoryInterface
{
    private FactoryInterface $baseFactory;

    public function __construct(FactoryInterface $baseFactory)
    {
        $this->baseFactory = $baseFactory;
    }

    public function createForPayment(
        PaymentInterface $payment,
        string $transactionId,
        string $paymentUrl
    ): IngTransactionInterface {
        $result = $this->createNew();

        $result->setTransactionId($transactionId);
        $result->setPayment($payment);
        $result->setPaymentUrl($paymentUrl);

        return $result;
    }

    public function createNew(): IngTransactionInterface
    {
        /** @var IngTransactionInterface $result */
        $result = $this->baseFactory->createNew();

        return $result;
    }
}
