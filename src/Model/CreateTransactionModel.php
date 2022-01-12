<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model;

final class CreateTransactionModel
{
    private string $type;

    private string $serviceId;

    private int $amount;

    private string $currency;

    private string $title;

    private string $orderId;

    private string $paymentMethod;

    private string $paymentMethodCode;

    private string $successReturnUrl;

    private string $failureReturnUrl;

    private CustomerModel $customer;

    private BillingModel $billing;

    private ShippingModel $shipping;

    public function __construct(
        string $type,
        string $serviceId,
        int $amount,
        string $currency,
        string $title,
        string $orderId,
        string $paymentMethod,
        string $paymentMethodCode,
        string $successReturnUrl,
        string $failureReturnUrl,
        CustomerModel $customer,
        BillingModel $billing,
        ShippingModel $shipping
    ) {
        $this->type = $type;
        $this->serviceId = $serviceId;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->title = $title;
        $this->orderId = $orderId;
        $this->paymentMethod = $paymentMethod;
        $this->paymentMethodCode = $paymentMethodCode;
        $this->successReturnUrl = $successReturnUrl;
        $this->failureReturnUrl = $failureReturnUrl;
        $this->customer = $customer;
        $this->billing = $billing;
        $this->shipping = $shipping;
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

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getPaymentMethodCode(): string
    {
        return $this->paymentMethodCode;
    }

    public function getSuccessReturnUrl(): string
    {
        return $this->successReturnUrl;
    }

    public function getFailureReturnUrl(): string
    {
        return $this->failureReturnUrl;
    }

    public function getCustomer(): CustomerModel
    {
        return $this->customer;
    }

    public function getBilling(): BillingModel
    {
        return $this->billing;
    }

    public function getShipping(): ShippingModel
    {
        return $this->shipping;
    }
}
