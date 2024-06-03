<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Entity;

use Sylius\Component\Core\Model\PaymentInterface;

interface ImojeTransactionInterface
{
    public function getId(): int;

    public function getTransactionId(): string;

    public function getPayment(): PaymentInterface;

    public function getPaymentUrl(): ?string;

    public function getServiceId(): string;

    public function getOrderId(): string;

    public function getGatewayCode(): string;
}
