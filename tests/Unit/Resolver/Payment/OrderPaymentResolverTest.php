<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPlugin\Unit\Resolver\Payment;

use BitBag\SyliusIngPlugin\Resolver\Payment\OrderPaymentResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\Payment;
use Sylius\Component\Core\Model\PaymentInterface;

final class OrderPaymentResolverTest extends TestCase
{
    private PaymentInterface $payment;

    private OrderInterface $order;

    protected function setUp(): void
    {
        $this->payment = new Payment();
        $this->order = $this->createMock(OrderInterface::class);
    }

    public function testResolveWithNewPayment(): void
    {
        $this->order
            ->expects(self::once())
            ->method('getLastPayment')
            ->with(PaymentInterface::STATE_NEW)
            ->willReturn($this->payment);

        $resolver = new OrderPaymentResolver();

        self::assertEquals($this->payment, $resolver->resolve($this->order));
    }

    public function testResolveFails(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->order
            ->expects(self::any())
            ->method('getLastPayment')
            ->willReturn(null);

        $resolver = new OrderPaymentResolver();

        self::assertEquals($this->payment, $resolver->resolve($this->order));
    }
}
