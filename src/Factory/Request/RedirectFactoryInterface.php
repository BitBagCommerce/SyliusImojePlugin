<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Request;

use BitBag\SyliusIngPlugin\Model\RedirectModelInterface;

interface RedirectFactoryInterface
{
    public function create(): RedirectModelInterface;
}
