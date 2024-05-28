<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\Refund;

interface RefundModelInterface
{
    public function getType(): string;

    public function getServiceId(): string;

    public function getAmount(): int;
}
