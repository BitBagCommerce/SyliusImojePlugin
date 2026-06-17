<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Repository\IngPayTransaction;

use BitBag\SyliusIngPayPlugin\Entity\IngPayTransaction;
use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use BitBag\SyliusIngPayPlugin\Exception\MissingIngPayTransactionException;
use BitBag\SyliusIngPayPlugin\Exception\NoTransactionException;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class IngPayTransactionRepository extends EntityRepository implements IngPayTransactionRepositoryInterface
{
    public function getByPaymentId(int $paymentId): IngPayTransaction
    {
        $transaction = $this->createQueryBuilder('o')
            ->innerJoin('o.payment', 'payment')
            ->where('payment.id = :paymentId')
            ->setParameter('paymentId', $paymentId)
            ->getQuery()
            ->getResult()
        ;

        if (null === $transaction) {
            throw new NoTransactionException('Could not find transaction');
        }

        $resultTransaction = end($transaction);

        return $resultTransaction;
    }

    public function getOneByTransactionId(string $transactionId): IngPayTransactionInterface
    {
        $transaction = $this->findOneBy([
            'transactionId' => $transactionId,
        ]);

        if (null === $transaction) {
            throw new MissingIngPayTransactionException($transactionId);
        }

        return $transaction;
    }
}
