<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Status;

use BitBag\SyliusIngPayPlugin\Model\Status\StatusResponseModelInterface;

interface StatusResponseModelFactoryInterface
{
    public function create(
        string $transactionId,
        string $paymentId,
        string $orderId,
        string $status,
    ): StatusResponseModelInterface;
}
