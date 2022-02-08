<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Handler;

use BitBag\SyliusIngPlugin\Bus\Command\SaveTransaction;
use BitBag\SyliusIngPlugin\Factory\Transaction\IngTransactionFactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SaveTransactionHandler implements MessageHandlerInterface
{
    private IngTransactionFactoryInterface $transactionFactory;

    private RepositoryInterface $transactionRepository;

    public function __construct(
        IngTransactionFactoryInterface $transactionFactory,
        RepositoryInterface $transactionRepository
    ) {
        $this->transactionFactory = $transactionFactory;
        $this->transactionRepository = $transactionRepository;
    }

    public function __invoke(SaveTransaction $command): void
    {
        $transaction = $this->transactionFactory->createForPayment($command->getPayment(), $command->getTransactionId());

        $this->transactionRepository->add($transaction);
    }
}
