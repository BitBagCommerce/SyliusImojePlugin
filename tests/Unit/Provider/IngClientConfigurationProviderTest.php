<?php

declare(strict_types=1);

namespace Unit\Provider;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Exception\IngNotConfiguredException;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProvider;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Repository\PaymentMethodRepositoryInterface;
use BitBag\SyliusIngPlugin\Resolver\Configuration\ConfigurationResolverInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Model\GatewayConfig;
use Sylius\Component\Core\Model\PaymentMethodInterface;

final class IngClientConfigurationProviderTest extends TestCase
{
    /** @var PaymentMethodRepositoryInterface|MockObject */
    private object $paymentMethodRepository;

    /** @var ConfigurationResolverInterface|MockObject */
    private object $configurationResolver;

    private IngClientConfigurationProviderInterface $ingClientConfigurationProvider;

    protected function setUp(): void
    {
        $this->paymentMethodRepository = $this->createMock(PaymentMethodRepositoryInterface::class);
        $this->configurationResolver = $this->createMock(ConfigurationResolverInterface::class);
        $this->ingClientConfigurationProvider = new IngClientConfigurationProvider(
            $this->paymentMethodRepository,
            $this->configurationResolver
        );
    }

    public function test_create_client_configuration(): void
    {
        $paymentMethod = $this->createMock(PaymentMethodInterface::class);
        $gatewayConfig = $this->createMock(GatewayConfig::class);

        $this->paymentMethodRepository
            ->expects($this->once())
            ->method('getOneForIngCode')
            ->with('code')
            ->willReturn($paymentMethod);

        $paymentMethod
            ->expects($this->once())
            ->method('getGatewayConfig')
            ->willReturn($gatewayConfig);

        $gatewayConfig
            ->expects($this->once())
            ->method('getConfig')
            ->willReturn([]);

        $this->configurationResolver
            ->expects($this->once())
            ->method('resolve')
            ->with([])
            ->willReturn([
                'token' => 'token',
                'merchantId' => 'merchantId',
                'redirect' => true,
                'sandboxUrl' => 'sandboxUrl',
                'prodUrl' => 'prodUrl',
                'isProd' => true,
        ]);

        self::assertInstanceOf(
            IngClientConfigurationInterface::class,
            $this->ingClientConfigurationProvider->getPaymentMethodConfiguration('code')
        );
    }

    public function test_create_client_with_exception(): void
    {
        $this->paymentMethodRepository
            ->expects($this->once())
            ->method('getOneForIngCode')
            ->with('code')
            ->willReturn(null);

        $this->expectException(IngNotConfiguredException::class);

        $this->ingClientConfigurationProvider->getPaymentMethodConfiguration('code');
    }

    public function test_create_client_with_second_exception(): void
    {
        $paymentMethod = $this->createMock(PaymentMethodInterface::class);

        $this->paymentMethodRepository
            ->expects($this->once())
            ->method('getOneForIngCode')
            ->with('code')
            ->willReturn($paymentMethod);

        $paymentMethod
            ->expects($this->once())
            ->method('getGatewayConfig')
            ->willReturn(null);

        $this->expectException(IngNotConfiguredException::class);

        $this->ingClientConfigurationProvider->getPaymentMethodConfiguration('code');
    }
}
