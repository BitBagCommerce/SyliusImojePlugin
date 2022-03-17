<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPlugin\Unit\Resolver\Payment;

use BitBag\SyliusIngPlugin\Exception\IngNotConfiguredException;
use BitBag\SyliusIngPlugin\Filter\AvailablePaymentMethodsFilterInterface;
use BitBag\SyliusIngPlugin\Repository\PaymentMethodRepositoryInterface;
use BitBag\SyliusIngPlugin\Resolver\Payment\IngPaymentsMethodResolver;
use BitBag\SyliusIngPlugin\Resolver\TotalResolver\TotalResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\Model\PaymentMethodInterface;

final class IngPaymentMethodResolverTest extends TestCase
{
    private const ING_CODE = 'ing_code';

    private const SERVICE_ID = '123';

    private PaymentMethodRepositoryInterface $paymentMethodRepository;

    private AvailablePaymentMethodsFilterInterface $paymentMethodsFilter;

    private TotalResolverInterface $totalResolver;

    protected function setUp(): void
    {
        $this->paymentMethodRepository = $this->createMock(PaymentMethodRepositoryInterface::class);
        $this->paymentMethodsFilter = $this->createMock(AvailablePaymentMethodsFilterInterface::class);
        $this->totalResolver = $this->createMock(TotalResolverInterface::class);
    }

    public function testResolveWithNewPayment(): void
    {
        $config = [
            'isProd' => true,
            'serviceId' => self::SERVICE_ID,
            'pbl' => 'pbl',
            'ing' => 'ing',
            'ipko' => 'ipko',
        ];

        $this->totalResolver
            ->expects(self::once())
            ->method('resolve')
            ->willReturn(105);

        $this->paymentMethodsFilter
            ->expects(self::once())
            ->method('filter')
            ->willReturn([
                'pbl' => 'pbl',
                'ing' => 'ing',
                'ipko' => 'ipko'
            ]);

        $paymentMethod = new PaymentMethod();
        $paymentMethod->setCode(self::ING_CODE);

        $gatewayConfig = $this->createMock(GatewayConfigInterface::class);
        $gatewayConfig
            ->expects(self::once())
            ->method('getGatewayName')
            ->willReturn('');

        $paymentMethod->setGatewayConfig($gatewayConfig);
        $paymentMethodMock = $this->createMock(PaymentMethodInterface::class);

        $this->paymentMethodRepository
            ->expects(self::once())
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

        $ingPaymentsMethodResolver = new IngPaymentsMethodResolver(
            $this->paymentMethodRepository,
            $this->paymentMethodsFilter,
            $this->totalResolver
        );

        self::assertEqualsCanonicalizing($finalConfig, $ingPaymentsMethodResolver->resolve());
    }

    public function testResolveEmptyPaymentException(): void
    {
        $this->expectException(IngNotConfiguredException::class);

        $this->totalResolver
            ->expects(self::once())
            ->method('resolve')
            ->willReturn(105);

        $this->paymentMethodRepository
            ->expects(self::once())
            ->method('findOneForIng')
            ->willReturn(null);


        $ingPaymentsMethodResolver = new IngPaymentsMethodResolver(
            $this->paymentMethodRepository,
            $this->paymentMethodsFilter,
            $this->totalResolver
        );
        $ingPaymentsMethodResolver->resolve();
    }
    public function testResolveEmptyConfigException(): void
    {
        $this->expectException(IngNotConfiguredException::class);

        $this->totalResolver
            ->expects(self::once())
            ->method('resolve')
            ->willReturn(105);

        $paymentMethodMock = $this->createMock(PaymentMethodInterface::class);
        $paymentMethod = new PaymentMethod();

        $this->paymentMethodRepository
            ->expects(self::once())
            ->method('findOneForIng')
            ->willReturn($paymentMethod);


        $paymentMethodMock
            ->method('getGatewayConfig')
            ->willReturn(null);

        $ingPaymentsMethodResolver = new IngPaymentsMethodResolver(
            $this->paymentMethodRepository,
            $this->paymentMethodsFilter,
            $this->totalResolver
        );
        $ingPaymentsMethodResolver->resolve();
    }
}
