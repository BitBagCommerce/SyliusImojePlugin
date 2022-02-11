<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model\Blik;

use BitBag\SyliusIngPlugin\Model\Blik\BlikModel;
use BitBag\SyliusIngPlugin\Model\Blik\BlikModelInterface;

final class BlikModelFactory implements BlikModelFactoryInterface
{
    public function create(string $blikCode, string $clientIp): BlikModelInterface
    {
        return new BlikModel($blikCode, $clientIp);
    }
}
