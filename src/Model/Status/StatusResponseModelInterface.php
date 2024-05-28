<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\Status;

interface StatusResponseModelInterface
{
    public function getTransactionId(): string;

    public function getPaymentId(): string;

    public function getOrderId(): string;

    public function getStatus(): string;
}
