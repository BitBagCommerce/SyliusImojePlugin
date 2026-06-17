<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Provider\BlikModel;

use BitBag\SyliusIngPayPlugin\Model\Blik\BlikModelInterface;

interface BlikModelProviderInterface
{
    public function provideDataToBlikModel(?string $blikCode): BlikModelInterface;
}
