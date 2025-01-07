<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Command\SaveTransaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SaveTransactionHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(SaveTransaction $command): void
    {
        $transaction = $command->getImojeTransaction();

        if (!$transaction) {
            throw new \InvalidArgumentException('Transaction cannot be null.');
        }

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}
