<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Refund;

use BitBag\SyliusIngPayPlugin\Model\Refund\RefundModelInterface;

interface RefundModelFactoryInterface
{
    public function create(
        string $type,
        string $serviceId,
        int $amount,
    ): RefundModelInterface;
}
