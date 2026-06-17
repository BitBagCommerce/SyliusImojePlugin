<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Status;

use BitBag\SyliusIngPayPlugin\Model\Status\StatusResponseModel;
use BitBag\SyliusIngPayPlugin\Model\Status\StatusResponseModelInterface;

final class StatusResponseModelFactory implements StatusResponseModelFactoryInterface
{
    public function create(
        string $transactionId,
        string $paymentId,
        string $orderId,
        string $status,
    ): StatusResponseModelInterface {
        return new StatusResponseModel($transactionId, $paymentId, $orderId, $status);
    }
}
