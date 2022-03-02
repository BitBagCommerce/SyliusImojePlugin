<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Configuration;

final class IngClientConfiguration implements IngClientConfigurationInterface
{
    private string $token;

    private string $merchantId;

    private string $sandboxUrl;

    private string $prodUrl;

    private bool $isProd;

    private string $serviceId;

    private string $shopKey;

    public function __construct(
        string $token,
        string $merchantId,
        string $sandboxUrl,
        string $prodUrl,
        bool $isProd,
        string $serviceId,
        string $shopKey
    ) {
        $this->token = $token;
        $this->merchantId = $merchantId;
        $this->sandboxUrl = $sandboxUrl;
        $this->prodUrl = $prodUrl;
        $this->isProd = $isProd;
        $this->serviceId = $serviceId;
        $this->shopKey = $shopKey;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getSandboxUrl(): string
    {
        return $this->sandboxUrl;
    }

    public function getProdUrl(): string
    {
        return $this->prodUrl;
    }

    public function isProd(): bool
    {
        return $this->isProd;
    }

    public function getShopKey(): string
    {
        return $this->shopKey;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }
}
