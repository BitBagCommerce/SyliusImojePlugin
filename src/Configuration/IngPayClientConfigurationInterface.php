<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Configuration;

interface IngPayClientConfigurationInterface
{
    public function getToken(): string;

    public function getMerchantId(): string;

    public function getSandboxUrl(): string;

    public function getProdUrl(): string;

    public function isProd(): bool;

    public function getServiceId(): string;

    public function getShopKey(): string;
}
