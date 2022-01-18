<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Sylius\Component\Core\Model\PaymentMethodInterface;

final class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    /**
     * @var EntityRepository<PaymentMethodInterface>
     */
    private EntityRepository $baseRepository;

    /** @param EntityRepository<PaymentMethodInterface> $baseRepository */
    public function __construct(EntityRepository $baseRepository)
    {
        $this->baseRepository = $baseRepository;
    }

    public function getOneForIngCode(string $code): PaymentMethodInterface
    {
        return $this->baseRepository->createQueryBuilder('o')
            ->innerJoin('o.gatewayConfig', 'gatewayConfig')
            ->where('gatewayConfig.factoryName = :factoryName')
            ->andWhere('o.code = :code')
            ->setParameter('factoryName', self::FACTORY_NAME)
            ->setParameter('code', $code)
            ->getQuery()
            ->getSingleResult()
            ;
    }

    public function findOneForIngCode(string $code): ?PaymentMethodInterface
    {
        try {
            return $this->getOneForIngCode($code);
        } catch (NoResultException $ex) {
            return null;
        }
    }
}
