<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\Status;

final class StatusResponseModel implements StatusResponseModelInterface
{
    private string $transactionId;

    private string $paymentId;

    private string $orderId;

    private string $status;

    public function __construct(
        string $transactionId,
        string $paymentId,
        string $orderId,
        string $status,
    ) {
        $this->transactionId = $transactionId;
        $this->paymentId = $paymentId;
        $this->orderId = $orderId;
        $this->status = $status;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
