<?php

declare(strict_types=1);

namespace Unit\Resolver\TransactionMethod;

use BitBag\SyliusIngPlugin\Resolver\TransactionMethod\TransactionMethodResolver;
use BitBag\SyliusIngPlugin\Resolver\TransactionMethod\TransactionMethodResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\PaymentInterface;

final class TransactionMethodResolverTest extends TestCase
{
    public const PAYMENT_METHOD_BLIK = 'blik';

    public const PAYMENT_METHOD_CARD = 'card';

    public const PAYMENT_METHOD_ING = 'ing';

    public const PAYMENT_METHOD_PBL = 'pbl';

    protected TransactionMethodResolverInterface $transactionMethodResolver;

    protected PaymentInterface $payment;

    protected function setUp(): void
    {
        $this->transactionMethodResolver = new TransactionMethodResolver();
        $this->payment = $this->createMock(PaymentInterface::class);
    }

    /**
     * @dataProvider provider
     */
    public function testResolveStatus(array $status, string $result): void
    {
        $this->payment
            ->method('getDetails')
            ->willReturn($status);

        self::assertEquals($result, $this->transactionMethodResolver->resolve($this->payment));
    }

    public function provider()
    {
        return [
            [[self::PAYMENT_METHOD_BLIK => self::PAYMENT_METHOD_BLIK], self::PAYMENT_METHOD_BLIK],
            [[self::PAYMENT_METHOD_CARD => self::PAYMENT_METHOD_CARD], self::PAYMENT_METHOD_CARD],
            [[self::PAYMENT_METHOD_ING => self::PAYMENT_METHOD_ING], self::PAYMENT_METHOD_ING],
            [[self::PAYMENT_METHOD_PBL => self::PAYMENT_METHOD_PBL], self::PAYMENT_METHOD_PBL],
        ];
    }
}
