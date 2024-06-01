<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Repository\ImojeTransaction;

use BitBag\SyliusImojePlugin\Entity\ImojeTransaction;
use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Exception\MissingImojeTransactionException;
use BitBag\SyliusImojePlugin\Exception\NoTransactionException;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class ImojeTransactionRepository extends EntityRepository implements ImojeTransactionRepositoryInterface
{
    public function getByPaymentId(int $paymentId): ImojeTransaction
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

    public function getOneByTransactionId(string $transactionId): ImojeTransactionInterface
    {
        $transaction = $this->findOneBy([
            'transactionId' => $transactionId,
        ]);

        if (null === $transaction) {
            throw new MissingImojeTransactionException($transactionId);
        }

        return $transaction;
    }
}
