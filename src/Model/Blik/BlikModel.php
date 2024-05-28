<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\Blik;

final class BlikModel implements BlikModelInterface
{
    private string $blikCode;

    private string $clientIp;

    public function __construct(string $blikCode, string $clientIp)
    {
        $this->blikCode = $blikCode;
        $this->clientIp = $clientIp;
    }

    public function getBlikCode(): string
    {
        return $this->blikCode;
    }

    public function getClientIp(): string
    {
        return $this->clientIp;
    }
}
