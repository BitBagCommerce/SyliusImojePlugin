<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\TransactionId;

interface TransactionIdResolverInterface
{
    public function resolve(string $content): string;
}
