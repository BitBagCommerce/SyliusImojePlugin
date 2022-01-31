<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model;

final class RedirectModel implements RedirectModelInterface
{
    private string $successUrl;

    private string $failureUrl;

    public function getSuccessUrl(): string
    {
        return $this->successUrl;
    }

    public function setSuccessUrl(string $successUrl): void
    {
        $this->successUrl = $successUrl;
    }

    public function getFailureUrl(): string
    {
        return $this->failureUrl;
    }

    public function setFailureUrl(string $failureUrl): void
    {
        $this->failureUrl = $failureUrl;
    }
}
