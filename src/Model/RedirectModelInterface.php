<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model;

interface RedirectModelInterface
{
    public function getSuccessUrl(): string;

    public function setSuccessUrl(string $successUrl): void;

    public function getFailureUrl(): string;

    public function setFailureUrl(string $failureUrl): void;
}
