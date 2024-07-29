<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Payment;

use BitBag\SyliusImojePlugin\Repository\ImojeTransaction\ImojeTransactionRepositoryInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class ImojeTransactionPaymentResolver implements ImojeTransactionPaymentResolverInterface
{
    private ImojeTransactionRepositoryInterface $transactionRepository;

    public function __construct(
        ImojeTransactionRepositoryInterface $transactionRepository,
    ) {
        $this->transactionRepository = $transactionRepository;
    }

    public function resolve(string $transactionId): PaymentInterface
    {
        $transaction = $this->transactionRepository->getOneByTransactionId($transactionId);

        return $transaction->getPayment();
    }
}
