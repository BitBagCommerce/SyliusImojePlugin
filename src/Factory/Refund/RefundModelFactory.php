<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Refund;

use BitBag\SyliusIngPlugin\Model\Refund\RefundModel;
use BitBag\SyliusIngPlugin\Model\Refund\RefundModelInterface;

final class RefundModelFactory implements RefundModelFactoryInterface
{
    public function create(
        string $type,
        string $serviceId,
        int $amount
    ): RefundModelInterface {
        return new RefundModel($type, $serviceId, $amount);
    }
}
