<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Repository\IngTransaction;

use BitBag\SyliusIngPlugin\Entity\IngTransaction;
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
            ->getOneOrNullResult()
            ;

        if ($transaction === null) {
            throw new NoTransactionException('Could not find transaction');
        }

        return $transaction;
    }
}
