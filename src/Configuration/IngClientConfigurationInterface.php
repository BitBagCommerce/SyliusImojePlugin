<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Configuration;

interface IngClientConfigurationInterface
{
    public function getToken(): string;

    public function getMerchantId(): string;

    public function isRedirect(): bool;

    public function getSandboxUrl(): string;

    public function getProdUrl(): string;

    public function isProd(): bool;
}
