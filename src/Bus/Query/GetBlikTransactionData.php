<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Query;

use BitBag\SyliusIngPlugin\Model\Blik\BlikModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class GetBlikTransactionData
{
    private OrderInterface $order;

    private string $code;

    private string $paymentMethod;

    private string $paymentMethodCode;

    private BlikModelInterface $blikModel;

    public function __construct(
        OrderInterface $order,
        string $code,
        string $paymentMethod,
        string $paymentMethodCode,
        BlikModelInterface $blikModel
    ) {
        $this->order = $order;
        $this->code = $code;
        $this->paymentMethod = $paymentMethod;
        $this->paymentMethodCode = $paymentMethodCode;
        $this->blikModel = $blikModel;
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

    public function getBlikModel(): BlikModelInterface
    {
        return $this->blikModel;
    }
}
