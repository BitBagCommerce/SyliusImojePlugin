<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Payment;

use BitBag\SyliusIngPlugin\Repository\IngTransaction\IngTransactionRepositoryInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class IngTransactionPaymentResolver implements IngTransactionPaymentResolverInterface
{
    private IngTransactionRepositoryInterface $transactionRepository;

    public function __construct(
        IngTransactionRepositoryInterface $transactionRepository
    ) {
        $this->transactionRepository = $transactionRepository;
    }

    public function resolve(string $transactionId): PaymentInterface
    {
        $transaction = $this->transactionRepository->getOneByTransactionId($transactionId);

        return $transaction->getPayment();
    }
}
