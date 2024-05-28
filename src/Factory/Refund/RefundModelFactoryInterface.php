<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Refund;

use BitBag\SyliusImojePlugin\Model\Refund\RefundModelInterface;

interface RefundModelFactoryInterface
{
    public function create(
        string $type,
        string $serviceId,
        int $amount
    ): RefundModelInterface;
}
