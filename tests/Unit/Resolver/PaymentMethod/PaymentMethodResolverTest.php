<?php

declare(strict_types=1);

namespace Unit\Resolver\PaymentMethod;

use BitBag\SyliusIngPlugin\Resolver\PaymentMethod\PaymentMethodResolver;
use BitBag\SyliusIngPlugin\Resolver\PaymentMethod\PaymentMethodResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethod;

final class PaymentMethodResolverTest extends TestCase
{
    protected PaymentMethodResolverInterface $paymentMethodResolver;

    protected PaymentInterface $payment;

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
}
