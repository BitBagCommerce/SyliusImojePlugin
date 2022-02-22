<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\TransactionId;

use BitBag\SyliusIngPlugin\Exception\NoTransactionException;

final class TransactionIdResolver implements TransactionIdResolverInterface
{
    public function resolve(string $content): string
    {
        $data = json_decode($content, true);

        if ($data['transaction'] === null || $data['transaction']['id'] === null) {
            throw new NoTransactionException('No data in request');
        }

        return $data['transaction']['id'];
    }
}
