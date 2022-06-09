<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Repository\IngTransaction;

use BitBag\SyliusIngPlugin\Entity\IngTransaction;
use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Exception\MissingIngTransactionException;
use BitBag\SyliusIngPlugin\Exception\NoTransactionException;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class IngTransactionRepository extends EntityRepository implements IngTransactionRepositoryInterface
{
    public function getByPaymentId(int $paymentId): IngTransaction
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

    public function getOneByTransactionId(string $transactionId): IngTransactionInterface
    {
        $transaction = $this->findOneBy([
            'transactionId' => $transactionId,
        ]);

        if (null === $transaction) {
            throw new MissingIngTransactionException($transactionId);
        }

        return $transaction;
    }
}
