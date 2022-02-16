<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Repository\IngTransaction;

use BitBag\SyliusIngPlugin\Entity\IngTransaction;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class IngTransactionRepository extends EntityRepository implements IngTransactionRepositoryInterface
{
    public function findByPaymentId(int $paymentId): ?IngTransaction
    {
        return $this->findOneBy(['payment_id' => $paymentId]);
    }
}
