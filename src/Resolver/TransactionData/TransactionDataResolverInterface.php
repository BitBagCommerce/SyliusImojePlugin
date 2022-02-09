<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\TransactionData;

use Psr\Http\Message\ResponseInterface;

interface TransactionDataResolverInterface
{
    public function resolve(ResponseInterface $response): array;
}
