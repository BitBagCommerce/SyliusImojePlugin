<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Status;

use BitBag\SyliusIngPlugin\Model\Status\StatusResponseModelInterface;

interface StatusResponseModelFactoryInterface
{
    public function create(
        string $transactionId,
        string $paymentId,
        string $orderId,
        string $status
    ): StatusResponseModelInterface;
}
