<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Model\Blik;

use BitBag\SyliusImojePlugin\Model\Blik\BlikModelInterface;

interface BlikModelFactoryInterface
{
    public function create(string $blikCode, string $clientIp): BlikModelInterface;
}
