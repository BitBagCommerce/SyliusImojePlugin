<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Configuration;

interface IngClientConfigurationInterface
{
    public function getToken(): string;

    public function getMerchantId(): string;

    public function getSandboxUrl(): string;

    public function getProdUrl(): string;

    public function isProd(): bool;

    public function getServiceId(): string;

    public function getShopKey(): string;
}
