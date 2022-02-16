<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Entity;

use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class IngTransaction implements IngTransactionInterface, ResourceInterface, TimestampableInterface
{
    use TimestampableTrait;

    protected int $id;

    protected string $transactionId;

    protected PaymentInterface $payment;

    protected string $paymentUrl;

    protected string $serviceId;

    protected string $orderId;

    protected string $gatewayCode;

    public function __construct(
        string $transactionId,
        PaymentInterface $payment,
        string $paymentUrl,
        string $serviceId,
        string $orderId,
        string $gatewayCode
    ) {
        $this->transactionId = $transactionId;
        $this->payment = $payment;
        $this->paymentUrl = $paymentUrl;
        $this->serviceId = $serviceId;
        $this->orderId = $orderId;
        $this->gatewayCode = $gatewayCode;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getPayment(): PaymentInterface
    {
        return $this->payment;
    }

    public function getPaymentUrl(): string
    {
        return $this->paymentUrl;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getGatewayCode(): string
    {
        return $this->gatewayCode;
    }
}
