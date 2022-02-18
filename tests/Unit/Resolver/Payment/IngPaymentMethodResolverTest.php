<?php

declare(strict_types=1);

namespace Unit\Resolver\Payment;

use BitBag\SyliusIngPlugin\Exception\IngNotConfiguredException;
use BitBag\SyliusIngPlugin\Repository\PaymentMethodRepositoryInterface;
use BitBag\SyliusIngPlugin\Resolver\Payment\IngPaymentsMethodResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\Model\PaymentMethodInterface;

final class IngPaymentMethodResolverTest extends TestCase
{
    protected const ING_CODE = 'ing_code';

    protected PaymentMethodRepositoryInterface $paymentMethodRepository;

    protected function setUp(): void
    {
        $this->paymentMethodRepository = $this->createMock(PaymentMethodRepositoryInterface::class);
    }

    public function testResolveWithNewPayment(): void
    {
        $config = [
            'isProd' => true,
            'pbl' => 'pbl',
            'ing' => 'ing',
            'ipko' => 'ipko',
        ];

        $paymentMethod = new PaymentMethod();
        $paymentMethod->setCode(self::ING_CODE);
        $gatewayConfig = $this->createMock(GatewayConfigInterface::class);
        $paymentMethod->setGatewayConfig($gatewayConfig);
        $paymentMethodMock = $this->createMock(PaymentMethodInterface::class);

        $this->paymentMethodRepository
            ->expects($this->once())
            ->method('findOneForIng')
            ->willReturn($paymentMethod);

        $paymentMethodMock
            ->method('getGatewayConfig')
            ->willReturn($gatewayConfig);

        $gatewayConfig
            ->method('getConfig')
            ->willReturn($config);

        $finalConfig = [
            'pbl' => 'pbl',
            'ing' => 'ing',
            'ipko' => 'ipko',
        ];

        $ingPaymentsMethodResolver = new IngPaymentsMethodResolver($this->paymentMethodRepository);

        self::assertEqualsCanonicalizing($finalConfig, $ingPaymentsMethodResolver->resolve());
    }

    public function testResolveEmptyPaymentException(): void
    {
        $this->paymentMethodRepository
            ->expects($this->once())
            ->method('findOneForIng')
            ->willReturn(null);

        $this->expectException(IngNotConfiguredException::class);

        $ingPaymentsMethodResolver = new IngPaymentsMethodResolver($this->paymentMethodRepository);
        $ingPaymentsMethodResolver->resolve();
    }
    public function testResolveEmptyConfigException(): void
    {
        $paymentMethodMock = $this->createMock(PaymentMethodInterface::class);
        $paymentMethod = new PaymentMethod();

        $this->paymentMethodRepository
            ->expects($this->once())
            ->method('findOneForIng')
            ->willReturn($paymentMethod);


        $paymentMethodMock
            ->method('getGatewayConfig')
            ->willReturn(null);

        $this->expectException(IngNotConfiguredException::class);

        $ingPaymentsMethodResolver = new IngPaymentsMethodResolver($this->paymentMethodRepository);
        $ingPaymentsMethodResolver->resolve();
    }
}
