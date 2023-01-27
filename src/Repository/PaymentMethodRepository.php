<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Repository;

use Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\PaymentMethodInterface;

final class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    /** @var EntityRepository<PaymentMethodInterface> */
    private EntityRepository $baseRepository;

    /** @param EntityRepository<PaymentMethodInterface> $baseRepository */
    public function __construct(EntityRepository $baseRepository)
    {
        $this->baseRepository = $baseRepository;
    }

    public function findOneForIngCode(string $code): ?PaymentMethodInterface
    {
        return $this->baseRepository->createQueryBuilder('o')
            ->innerJoin('o.gatewayConfig', 'gatewayConfig')
            ->where('gatewayConfig.factoryName = :factoryName')
            ->andWhere('o.code = :code')
            ->setParameter('factoryName', self::FACTORY_NAME)
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findOneForIng(): ?PaymentMethodInterface
    {
        $result = $this->baseRepository->createQueryBuilder('o')
            ->innerJoin('o.gatewayConfig', 'gatewayConfig')
            ->where('gatewayConfig.factoryName = :factoryName')
            ->setParameter('factoryName', self::FACTORY_NAME)
            ->getQuery()
            ->getResult()
            ;
        return  $result[0];
    }
}
