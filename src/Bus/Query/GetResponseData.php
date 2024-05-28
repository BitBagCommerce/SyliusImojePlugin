<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Query;

final class GetResponseData
{
    private int $paymentId;

    public function __construct(int $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    public function getPaymentId(): int
    {
        return $this->paymentId;
    }
}
