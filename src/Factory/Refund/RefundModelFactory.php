<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Refund;

use BitBag\SyliusImojePlugin\Model\Refund\RefundModel;
use BitBag\SyliusImojePlugin\Model\Refund\RefundModelInterface;

final class RefundModelFactory implements RefundModelFactoryInterface
{
    public function create(
        string $type,
        string $serviceId,
        int $amount,
    ): RefundModelInterface {
        return new RefundModel($type, $serviceId, $amount);
    }
}
