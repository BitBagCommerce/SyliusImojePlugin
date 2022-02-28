<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPlugin\Unit\Resolver\TransactionId;

use BitBag\SyliusIngPlugin\Exception\NoTransactionException;
use BitBag\SyliusIngPlugin\Resolver\TransactionId\TransactionIdResolver;
use BitBag\SyliusIngPlugin\Resolver\TransactionId\TransactionIdResolverInterface;
use PHPUnit\Framework\TestCase;

final class TransactionIdResolverTest extends TestCase
{
    protected const TANSACTION_ID = 'ab5fd6b4-ae9e-4ae7-b476-914258352718';
    protected TransactionIdResolverInterface $transactionIdResolver;

    protected function setUp(): void
    {
        $this->transactionIdResolver = new TransactionIdResolver();
    }

    public function testResolveTransactionId(): void
    {
        $arg = \sprintf('{"transaction":{"id":"%s","test":"test"}}', self::TANSACTION_ID);
        $result = $this->transactionIdResolver->resolve($arg);
        self::assertEquals(self::TANSACTION_ID, $result);
    }

    public function testResolveFailureTransactionId(): void
    {
        $this->expectException(NoTransactionException::class);
        $arg = '{"transaction":{"test":"test"}}';
        $this->transactionIdResolver->resolve($arg);
    }
}
