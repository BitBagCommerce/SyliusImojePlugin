<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Query;

use Sylius\Component\Core\Model\OrderInterface;

final class GetTransactionData
{
    private OrderInterface $order;

    private string $code;

    private string $paymentMethod;

    private string $paymentMethodCode;

    public function __construct(
        OrderInterface $order,
        string $code,
        string $paymentMethod,
        string $paymentMethodCode,
    ) {
        $this->order = $order;
        $this->code = $code;
        $this->paymentMethod = $paymentMethod;
        $this->paymentMethodCode = $paymentMethodCode;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getPaymentMethodCode(): string
    {
        return $this->paymentMethodCode;
    }
}
