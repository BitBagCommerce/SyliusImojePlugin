<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Model\Blik;

use BitBag\SyliusIngPayPlugin\Model\Blik\BlikModelInterface;

interface BlikModelFactoryInterface
{
    public function create(string $blikCode, string $clientIp): BlikModelInterface;
}
