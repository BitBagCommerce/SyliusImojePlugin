<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Configuration;

interface IngClientConfigurationInterface
{
    public function getToken(): string;

    public function isRedirect(): bool;

    public function getSandboxUrl(): string;

    public function isProdUrl(): bool;

    public function getIsProd(): string;
}
