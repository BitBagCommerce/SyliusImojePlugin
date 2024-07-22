<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Status;

use BitBag\SyliusImojePlugin\Model\Status\StatusResponseModelInterface;

interface StatusResponseModelFactoryInterface
{
    public function create(
        string $transactionId,
        string $paymentId,
        string $orderId,
        string $status,
    ): StatusResponseModelInterface;
}
