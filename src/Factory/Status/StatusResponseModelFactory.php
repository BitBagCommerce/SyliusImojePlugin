<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Status;

use BitBag\SyliusIngPlugin\Model\Status\StatusResponseModel;
use BitBag\SyliusIngPlugin\Model\Status\StatusResponseModelInterface;

final class StatusResponseModelFactory implements StatusResponseModelFactoryInterface
{
    public function create(
        string $transactionId,
        string $paymentId,
        string $orderId,
        string $status
    ): StatusResponseModelInterface {
        return new StatusResponseModel($transactionId, $paymentId, $orderId, $status);
    }
}
