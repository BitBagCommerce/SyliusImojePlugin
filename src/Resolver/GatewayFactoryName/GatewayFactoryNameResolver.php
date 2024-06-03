<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\GatewayFactoryName;

use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class GatewayFactoryNameResolver implements GatewayFactoryNameResolverInterface
{
    private RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function resolve(string $gatewayCode): string
    {
        /** @var GatewayConfigInterface|null $config */
        $config = $this->repository->findOneBy(['gatewayName' => $gatewayCode]);
        if (null === $config) {
            return '';
        }

        return $config->getFactoryName() ?? '';
    }
}
