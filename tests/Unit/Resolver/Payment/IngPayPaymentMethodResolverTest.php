<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPayPlugin\Unit\Resolver\Payment;

use BitBag\SyliusIngPayPlugin\Exception\IngPayNotConfiguredException;
use BitBag\SyliusIngPayPlugin\Filter\AvailablePaymentMethodsFilterInterface;
use BitBag\SyliusIngPayPlugin\Repository\PaymentMethodRepositoryInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Payment\IngPayPaymentsMethodResolver;
use BitBag\SyliusIngPayPlugin\Resolver\TotalResolver\TotalResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Order\Context\CartContextInterface;

final class IngPayPaymentMethodResolverTest extends TestCase
{
    private const ING_PAY_CODE = 'ing_pay_code';

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
            'ing_pay_paylater' => 'ing_pay_paylater',
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
        $paymentMethod->setCode(self::ING_PAY_CODE);

        $gatewayConfig = $this->createMock(GatewayConfigInterface::class);
        $gatewayConfig
            ->expects(self::once())
            ->method('getGatewayName')
            ->willReturn('');

        $paymentMethod->setGatewayConfig($gatewayConfig);
        $paymentMethodMock = $this->createMock(PaymentMethodInterface::class);

        $this->paymentMethodRepository
            ->expects(self::once())
            ->method('findOneForIngPay')
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

        $ingPayPaymentsMethodResolver = new IngPayPaymentsMethodResolver(
            $this->paymentMethodRepository,
            $this->paymentMethodsFilter,
            $this->totalResolver,
            $this->cartContext,
        );

        self::assertEqualsCanonicalizing($finalConfig, $ingPayPaymentsMethodResolver->resolve());
    }

    public function testResolveEmptyPaymentException(): void
    {
        $order = $this->createMock(OrderInterface::class);
        $this->expectException(IngPayNotConfiguredException::class);

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
            ->method('findOneForIngPay')
            ->willReturn(null);

        $ingPayPaymentsMethodResolver = new IngPayPaymentsMethodResolver(
            $this->paymentMethodRepository,
            $this->paymentMethodsFilter,
            $this->totalResolver,
            $this->cartContext,
        );
        $ingPayPaymentsMethodResolver->resolve();
    }

    public function testResolveEmptyConfigException(): void
    {
        $this->expectException(IngPayNotConfiguredException::class);

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
            ->method('findOneForIngPay')
            ->willReturn($paymentMethod);

        $paymentMethodMock
            ->method('getGatewayConfig')
            ->willReturn(null);

        $ingPayPaymentsMethodResolver = new IngPayPaymentsMethodResolver(
            $this->paymentMethodRepository,
            $this->paymentMethodsFilter,
            $this->totalResolver,
            $this->cartContext,
        );
        $ingPayPaymentsMethodResolver->resolve();
    }
}
