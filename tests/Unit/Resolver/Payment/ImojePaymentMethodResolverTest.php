<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusImojePlugin\Unit\Resolver\Payment;

use BitBag\SyliusImojePlugin\Exception\ImojeNotConfiguredException;
use BitBag\SyliusImojePlugin\Filter\AvailablePaymentMethodsFilterInterface;
use BitBag\SyliusImojePlugin\Repository\PaymentMethodRepositoryInterface;
use BitBag\SyliusImojePlugin\Resolver\Payment\ImojePaymentsMethodResolver;
use BitBag\SyliusImojePlugin\Resolver\TotalResolver\TotalResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Order\Context\CartContextInterface;

final class ImojePaymentMethodResolverTest extends TestCase
{
    private const IMOJE_CODE = 'imoje_code';

    private const SERVICE_ID = '123';

    private PaymentMethodRepositoryInterface $paymentMethodRepository;

    private AvailablePaymentMethodsFilterInterface $paymentMethodsFilter;

    private TotalResolverInterface $totalResolver;

    private CartContextInterface $cartContext;

    protected function setUp(): void
    {
        $this->paymentMethodRepository = $this->createMock(PaymentMethodRepositoryInterface::class);
        $this->paymentMethodsFilter = $this->createMock(AvailablePaymentMethodsFilterInterface::class);
        $this->totalResolver = $this->createMock(TotalResolverInterface::class);
        $this->cartContext = $this->createMock(CartContextInterface::class);
    }

    public function testResolveWithNewPayment(): void
    {
        $order = $this->createMock(OrderInterface::class);
        $payment = $this->createMock(PaymentInterface::class);

        $config = [
            'isProd' => true,
            'serviceId' => self::SERVICE_ID,
            'pbl' => 'pbl',
            'ing' => 'ing',
            'ipko' => 'ipko',
            'imoje_paylater' => 'imoje_paylater',
        ];

        $this->totalResolver
            ->expects(self::once())
            ->method('resolve')
            ->willReturn(105);

        $this->cartContext
            ->expects(self::once())
            ->method('getCart')
            ->willReturn($order);

        $order
            ->expects(self::once())
            ->method('getCurrencyCode')
            ->willReturn('PLN');

        $this->paymentMethodsFilter
            ->expects(self::once())
            ->method('filter')
            ->willReturn([
                'pbl' => 'pbl',
                'ing' => 'ing',
                'ipko' => 'ipko',
            ]);

        $paymentMethod = new PaymentMethod();
        $paymentMethod->setCode(self::IMOJE_CODE);

        $gatewayConfig = $this->createMock(GatewayConfigInterface::class);
        $gatewayConfig
            ->expects(self::once())
            ->method('getGatewayName')
            ->willReturn('');

        $paymentMethod->setGatewayConfig($gatewayConfig);
        $paymentMethodMock = $this->createMock(PaymentMethodInterface::class);

        $this->paymentMethodRepository
            ->expects(self::once())
            ->method('findOneForImoje')
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

        $imojePaymentsMethodResolver = new ImojePaymentsMethodResolver(
            $this->paymentMethodRepository,
            $this->paymentMethodsFilter,
            $this->totalResolver,
            $this->cartContext,
        );

        self::assertEqualsCanonicalizing($finalConfig, $imojePaymentsMethodResolver->resolve());
    }

    public function testResolveEmptyPaymentException(): void
    {
        $order = $this->createMock(OrderInterface::class);
        $this->expectException(ImojeNotConfiguredException::class);

        $this->cartContext
            ->expects(self::once())
            ->method('getCart')
            ->willReturn($order);

        $order
            ->expects(self::once())
            ->method('getCurrencyCode')
            ->willReturn('PLN');

        $this->totalResolver
            ->expects(self::once())
            ->method('resolve')
            ->willReturn(105);

        $this->paymentMethodRepository
            ->expects(self::once())
            ->method('findOneForImoje')
            ->willReturn(null);

        $imojePaymentsMethodResolver = new ImojePaymentsMethodResolver(
            $this->paymentMethodRepository,
            $this->paymentMethodsFilter,
            $this->totalResolver,
            $this->cartContext,
        );
        $imojePaymentsMethodResolver->resolve();
    }

    public function testResolveEmptyConfigException(): void
    {
        $this->expectException(ImojeNotConfiguredException::class);

        $order = $this->createMock(OrderInterface::class);
        $payment = $this->createMock(PaymentInterface::class);

        $this->totalResolver
            ->expects(self::once())
            ->method('resolve')
            ->willReturn(105);

        $this->cartContext
            ->expects(self::once())
            ->method('getCart')
            ->willReturn($order);

        $order
            ->expects(self::once())
            ->method('getCurrencyCode')
            ->willReturn('PLN');

        $paymentMethodMock = $this->createMock(PaymentMethodInterface::class);
        $paymentMethod = new PaymentMethod();

        $this->paymentMethodRepository
            ->expects(self::once())
            ->method('findOneForImoje')
            ->willReturn($paymentMethod);

        $paymentMethodMock
            ->method('getGatewayConfig')
            ->willReturn(null);

        $imojePaymentsMethodResolver = new ImojePaymentsMethodResolver(
            $this->paymentMethodRepository,
            $this->paymentMethodsFilter,
            $this->totalResolver,
            $this->cartContext,
        );
        $imojePaymentsMethodResolver->resolve();
    }
}
