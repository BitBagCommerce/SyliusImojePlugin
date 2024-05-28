<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusImojePlugin\Unit\Resolver\PaymentMethod;

use BitBag\SyliusImojePlugin\Resolver\PaymentMethod\PaymentMethodResolver;
use BitBag\SyliusImojePlugin\Resolver\PaymentMethod\PaymentMethodResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethod;

final class PaymentMethodResolverTest extends TestCase
{
    private PaymentMethodResolverInterface $paymentMethodResolver;

    private PaymentInterface $payment;

    protected function setUp(): void
    {
        $this->payment = $this->createMock(PaymentInterface::class);
        $this->paymentMethodResolver = new PaymentMethodResolver();
    }

    public function testResolvePaymentMethod(): void
    {
        $paymentMethod = new PaymentMethod();

        $this->payment
            ->expects(self::once())
            ->method('getMethod')
            ->willReturn($paymentMethod);

        $resolver = new PaymentMethodResolver();

        self::assertEquals($paymentMethod, $resolver->resolve($this->payment));
    }

    public function testResolveFails(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->payment
            ->expects(self::any())
            ->method('getMethod')
            ->willReturn(null);

        $resolver = new PaymentMethodResolver();

        self::assertEquals($this->payment, $resolver->resolve($this->payment));
    }
}
