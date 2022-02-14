<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Provider\BlikModel;

use BitBag\SyliusIngPlugin\Model\Blik\BlikModelInterface;

interface BlikModelProviderInterface
{
    public function provideDataToBlikModel(): BlikModelInterface;
}
