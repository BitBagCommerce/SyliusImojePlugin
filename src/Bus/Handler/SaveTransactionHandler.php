<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Command\SaveTransaction;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SaveTransactionHandler implements MessageHandlerInterface
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(SaveTransaction $command): void
    {
        $transaction = $command->getimojeTransaction();
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}
