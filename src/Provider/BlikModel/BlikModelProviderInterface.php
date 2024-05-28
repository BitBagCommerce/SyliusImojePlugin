<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider\BlikModel;

use BitBag\SyliusImojePlugin\Model\Blik\BlikModelInterface;

interface BlikModelProviderInterface
{
    public function provideDataToBlikModel(?string $blikCode): BlikModelInterface;
}
