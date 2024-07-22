<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider;

use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfiguration;
use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;
use BitBag\SyliusImojePlugin\Exception\ImojeNotConfiguredException;
use BitBag\SyliusImojePlugin\Repository\PaymentMethodRepositoryInterface;
use BitBag\SyliusImojePlugin\Resolver\Configuration\ConfigurationResolverInterface;
use Payum\Core\Model\GatewayConfigInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;

final class
ImojeClientConfigurationProvider implements ImojeClientConfigurationProviderInterface
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

    public function getPaymentMethodConfiguration(string $code): ImojeClientConfigurationInterface
    {
        $paymentMethod = $this->paymentMethodRepository->findOneForImojeCode($code);

        if (null === $paymentMethod) {
            throw new ImojeNotConfiguredException(
                \sprintf('Payment method with code %s is not configured', $code)
            );
        }

        $config = $this->getGatewayConfig($paymentMethod)->getConfig();
        $resolved = $this->configurationResolver->resolve($config);

        return new ImojeClientConfiguration(
            $resolved['token'],
            $resolved['merchantId'],
            $resolved['sandboxUrl'],
            $resolved['prodUrl'],
            $resolved['isProd'],
            $resolved['serviceId'],
            $resolved['shopKey'],
        );
    }

    private function getGatewayConfig(PaymentMethodInterface $paymentMethod): GatewayConfigInterface
    {
        $gatewayConfig = $paymentMethod->getGatewayConfig();

        if (null === $gatewayConfig) {
            throw new ImojeNotConfiguredException((string) $paymentMethod->getCode());
        }

        return $gatewayConfig;
    }
}
