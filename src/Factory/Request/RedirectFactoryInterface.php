<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Model\RedirectModelInterface;

interface RedirectFactoryInterface
{
    public function createNew(): RedirectModelInterface;
}
