<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\GatewayCode;

use BitBag\SyliusImojePlugin\Exception\NoImojeGatewayPaymentException;
use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class GatewayCodeResolver implements GatewayCodeResolverInterface
{
    private RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function resolve(string $factoryName): string
    {
        /** @var GatewayConfigInterface|null $config */
        $config = $this->repository->findOneBy(['factoryName' => $factoryName]);
        if (null === $config) {
            throw new NoImojeGatewayPaymentException('No gateway code found');
        }

        return $config->getGatewayName();
    }
}
