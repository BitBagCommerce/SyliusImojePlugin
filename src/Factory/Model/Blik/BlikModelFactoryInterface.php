<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model\Blik;

use BitBag\SyliusIngPlugin\Model\Blik\BlikModelInterface;

interface BlikModelFactoryInterface
{
    public function create(string $blikCode, string $clientIp): BlikModelInterface;
}
