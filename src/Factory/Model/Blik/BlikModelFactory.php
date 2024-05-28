<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Model\Blik;

use BitBag\SyliusImojePlugin\Model\Blik\BlikModel;
use BitBag\SyliusImojePlugin\Model\Blik\BlikModelInterface;

final class BlikModelFactory implements BlikModelFactoryInterface
{
    public function create(string $blikCode, string $clientIp): BlikModelInterface
    {
        return new BlikModel($blikCode, $clientIp);
    }
}
