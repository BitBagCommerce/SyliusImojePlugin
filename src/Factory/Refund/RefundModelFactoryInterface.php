<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Refund;

use BitBag\SyliusIngPlugin\Model\Refund\RefundModelInterface;

interface RefundModelFactoryInterface
{
    public function create(
        string $type,
        string $serviceId,
        int $amount
    ): RefundModelInterface;
}
