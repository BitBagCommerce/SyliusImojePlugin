<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Provider;

use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProvider;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Repository\PaymentMethodRepositoryInterface;
use BitBag\SyliusIngPlugin\Resolver\Configuration\ConfigurationResolverInterface;

final class IngClientConfigurationProviderFactory implements IngClientConfigurationProviderFactoryInterface
{
    private PaymentMethodRepositoryInterface $paymentMethodRepository;

    private ConfigurationResolverInterface $configurationResolver;

    public function __construct(
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        ConfigurationResolverInterface $configurationResolver
    ) {
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->configurationResolver = $configurationResolver;
    }

    public function createIngClientConfigurationProvider(): IngClientConfigurationProviderInterface
    {
        return new IngClientConfigurationProvider($this->paymentMethodRepository, $this->configurationResolver);
    }
}
