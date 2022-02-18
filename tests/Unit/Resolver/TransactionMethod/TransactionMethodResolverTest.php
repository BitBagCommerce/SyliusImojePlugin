<?php

declare(strict_types=1);

namespace Unit\Resolver\TransactionMethod;

use BitBag\SyliusIngPlugin\Resolver\TransactionMethod\TransactionMethodResolver;
use BitBag\SyliusIngPlugin\Resolver\TransactionMethod\TransactionMethodResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\PaymentInterface;

final class TransactionMethodResolverTest extends TestCase
{
    protected TransactionMethodResolverInterface $transactionMethodResolver;

    protected PaymentInterface $payment;

    protected function setUp(): void
    {
        $this->transactionMethodResolver = new TransactionMethodResolver();
        $this->payment = $this->createMock(PaymentInterface::class);
    }

    /**
     * @dataProvider statusProvider
     */
    public function testResolveStatus(array $status, string $result): void
    {
        $this->payment
            ->method('getDetails')
            ->willReturn($status);

        self::assertEquals($result, $this->transactionMethodResolver->resolve($this->payment));
    }

    public function statusProvider()
    {
        return [
            [['status' => 'blik'], 'blik'],
            [['status' => 'card'], 'card'],
            [['status'=> 'ing'], 'ing'],
            [['status'=> 'pbl'], 'pbl'],
        ];
    }
}
