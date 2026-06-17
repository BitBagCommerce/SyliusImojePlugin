<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Bus\Handler;

use BitBag\SyliusIngPayPlugin\Bus\Command\SaveTransaction;
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
        $transaction = $command->getingPayTransaction();
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}
