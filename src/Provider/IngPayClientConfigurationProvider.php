<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Provider;

use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfiguration;
use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;
use BitBag\SyliusIngPayPlugin\Exception\IngPayNotConfiguredException;
use BitBag\SyliusIngPayPlugin\Repository\PaymentMethodRepositoryInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Configuration\ConfigurationResolverInterface;
use Payum\Core\Model\GatewayConfigInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;

final class IngPayClientConfigurationProvider implements IngPayClientConfigurationProviderInterface
{
    private PaymentMethodRepositoryInterface $paymentMethodRepository;

    private ConfigurationResolverInterface $configurationResolver;

    public function __construct(
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        ConfigurationResolverInterface $configurationResolver,
    ) {
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->configurationResolver = $configurationResolver;
    }

    public function getPaymentMethodConfiguration(string $code): IngPayClientConfigurationInterface
    {
        $paymentMethod = $this->paymentMethodRepository->findOneForIngPayCode($code);

        if (null === $paymentMethod) {
            throw new IngPayNotConfiguredException(
                \sprintf('Payment method with code %s is not configured', $code),
            );
        }

        $config = $this->getGatewayConfig($paymentMethod)->getConfig();
        $resolved = $this->configurationResolver->resolve($config);

        return new IngPayClientConfiguration(
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
            throw new IngPayNotConfiguredException((string) $paymentMethod->getCode());
        }

        return $gatewayConfig;
    }
}
