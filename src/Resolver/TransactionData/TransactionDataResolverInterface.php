<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\TransactionData;

use Psr\Http\Message\ResponseInterface;

interface TransactionDataResolverInterface
{
    public function resolve(ResponseInterface $response): array;
}
