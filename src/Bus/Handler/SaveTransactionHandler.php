<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Handler;

use BitBag\SyliusIngPlugin\Bus\Command\SaveTransaction;
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
        $transaction = $command->getIngTransaction();
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}
