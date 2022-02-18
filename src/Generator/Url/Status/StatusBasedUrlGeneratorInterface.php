<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Generator\Url\Status;

use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Request;

interface StatusBasedUrlGeneratorInterface
{
    public function accepts(string $status): bool;

    public function generate(Request $request, OrderInterface $order): string;
}
