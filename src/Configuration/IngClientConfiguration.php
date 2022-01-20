<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Configuration;

final class IngClientConfiguration implements IngClientConfigurationInterface
{
    private string $token;

    private string $merchantId;

    private bool $redirect;

    private string $sandboxUrl;

    private string $prodUrl;

    private bool $isProd;

    public function __construct(string $token, string $merchantId, bool $redirect, string $sandboxUrl, string $prodUrl, bool $isProd)
    {
        $this->token = $token;
        $this->merchantId = $merchantId;
        $this->redirect = $redirect;
        $this->sandboxUrl = $sandboxUrl;
        $this->prodUrl = $prodUrl;
        $this->isProd = $isProd;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function isRedirect(): bool
    {
        return $this->redirect;
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
}
