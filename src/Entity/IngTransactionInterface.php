<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Entity;

use Sylius\Component\Core\Model\PaymentInterface;

interface IngTransactionInterface
{
    public function getId(): ?int;

    public function getTransactionId(): ?string;

    public function setTransactionId(?string $transactionId): void;

    public function getPayment(): ?PaymentInterface;

    public function setPayment(?PaymentInterface $payment): void;
}
