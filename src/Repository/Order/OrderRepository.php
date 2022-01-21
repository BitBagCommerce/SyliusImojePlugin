<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Repository\Order;

use Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\OrderInterface;

final class OrderRepository implements OrderRepositoryInterface
{
    /** @var EntityRepository<OrderInterface> */
    private EntityRepository $baseRepository;

    /** @param EntityRepository<OrderInterface> $baseRepository */
    public function __construct(EntityRepository $baseRepository)
    {
        $this->baseRepository = $baseRepository;
    }

    public function findByTokenValue(string $tokenValue): ?OrderInterface
    {
        return $this->baseRepository->findOneBy(['tokenValue' => $tokenValue]);
    }
}
