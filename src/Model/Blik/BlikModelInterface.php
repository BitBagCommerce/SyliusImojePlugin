<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\Blik;

interface BlikModelInterface
{
    public function getBlikCode(): string;

    public function getClientIp(): string;
}
