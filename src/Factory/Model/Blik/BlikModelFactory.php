<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Model\Blik;

use BitBag\SyliusIngPayPlugin\Model\Blik\BlikModel;
use BitBag\SyliusIngPayPlugin\Model\Blik\BlikModelInterface;

final class BlikModelFactory implements BlikModelFactoryInterface
{
    public function create(string $blikCode, string $clientIp): BlikModelInterface
    {
        return new BlikModel($blikCode, $clientIp);
    }
}
