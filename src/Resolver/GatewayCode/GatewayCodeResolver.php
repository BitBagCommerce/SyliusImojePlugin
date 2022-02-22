<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\GatewayCode;

use BitBag\SyliusIngPlugin\Repository\IngTransaction\IngTransactionRepositoryInterface;

final class GatewayCodeResolver implements GatewayCodeResolverInterface
{
    private IngTransactionRepositoryInterface $ingTransactionRepository;

    public function __construct(IngTransactionRepositoryInterface $ingTransactionRepository)
    {
        $this->ingTransactionRepository = $ingTransactionRepository;
    }

    public function resolve(string $transactionId): string
    {
        return $this->ingTransactionRepository->getOneByTransactionId($transactionId)->getGatewayCode();
    }
}
