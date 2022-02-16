<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Generator\Url\Status;

use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Request;

interface AggregateStatusBasedUrlGeneratorInterface
{
    public function generate(OrderInterface $order, Request $request, string $status): string;
}
