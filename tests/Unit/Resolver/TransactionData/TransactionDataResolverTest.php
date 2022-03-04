<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPlugin\Unit\Resolver\TransactionData;

use BitBag\SyliusIngPlugin\Resolver\TransactionData\TransactionDataResolver;
use BitBag\SyliusIngPlugin\Resolver\TransactionData\TransactionDataResolverInterface;
use GuzzleHttp\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class TransactionDataResolverTest extends TestCase
{
    protected const TRANSACTION_ID = '2f82-id-2f82';
    protected const SERVICE_ID = '2ec212fc-2fghm82';
    protected const ORDER_ID = '142';
    protected const TRANSACTION_URL = 'https://sandbox.paywall.test.pl';

    protected TransactionDataResolverInterface $transactionDataResolver;

    protected function setUp(): void
    {
        $this->transactionDataResolver = new TransactionDataResolver();
    }

    public function testResolveStatus(): void
    {
        $contentString = \sprintf(
            '{"transaction":{"id":"%s","serviceId":"%s","orderId":"%s"},"action":{"url":"%s"}}',
            self::TRANSACTION_ID,
            self::SERVICE_ID,
            self::ORDER_ID,
            self::TRANSACTION_URL
        );


        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->method('getBody')
            ->willReturn($stream);

        $stream
            ->method('getContents')
            ->willReturn($contentString);

        $result = $this->transactionDataResolver->resolve($response);
        $this->assertEquals(self::TRANSACTION_ID, $result['transactionId']);
    }
}
