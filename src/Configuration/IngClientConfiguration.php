<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Configuration;

final class IngClientConfiguration implements IngClientConfigurationInterface
{
    private string $token;

    private bool $redirect;

    private string $sandboxUrl;

    private bool $prodUrl;

    private string $isProd;

    private string $merchantId;

    public function __construct(string $token, bool $redirect, string $sandboxUrl, bool $prodUrl, string $isProd, string $merchantId)
    {
        $this->token = $token;
        $this->redirect = $redirect;
        $this->sandboxUrl = $sandboxUrl;
        $this->prodUrl = $prodUrl;
        $this->isProd = $isProd;
        $this->merchantId = $merchantId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function isRedirect(): bool
    {
        return $this->redirect;
    }

    public function getSandboxUrl(): string
    {
        return $this->sandboxUrl;
    }

    public function isProdUrl(): bool
    {
        return $this->prodUrl;
    }

    public function getIsProd(): string
    {
        return $this->isProd;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }
}
