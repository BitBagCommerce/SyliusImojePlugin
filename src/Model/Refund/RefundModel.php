<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\Refund;

final class RefundModel implements RefundModelInterface
{
    private string $type;

    private string $serviceId;

    private int $amount;

    public function __construct(
        string $type,
        string $serviceId,
        int $amount,
    ) {
        $this->type = $type;
        $this->serviceId = $serviceId;
        $this->amount = $amount;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
