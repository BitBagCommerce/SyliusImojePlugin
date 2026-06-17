<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Payment;

use BitBag\SyliusIngPayPlugin\Repository\IngPayTransaction\IngPayTransactionRepositoryInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class IngPayTransactionPaymentResolver implements IngPayTransactionPaymentResolverInterface
{
    private IngPayTransactionRepositoryInterface $transactionRepository;

    public function __construct(
        IngPayTransactionRepositoryInterface $transactionRepository,
    ) {
        $this->transactionRepository = $transactionRepository;
    }

    public function resolve(string $transactionId): PaymentInterface
    {
        $transaction = $this->transactionRepository->getOneByTransactionId($transactionId);

        return $transaction->getPayment();
    }
}
