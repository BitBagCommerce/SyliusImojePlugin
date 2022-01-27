<?php

declare(strict_types=1);

namespace Unit\Client;

use BitBag\SyliusIngPlugin\Client\IngApiClient;
use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use BitBag\SyliusIngPlugin\Factory\Serializer\SerializerFactoryInterface;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class IngApiClientTest extends TestCase
{
    /** @var Client|MockObject */
    private object $httpClient;

    /** @var SerializerFactoryInterface|MockObject */
    private object $serializerFactory;

    private IngApiClient $ingApiClient;

    public const TOKEN = 'token';
    public const URL = 'url/';

    protected function setUp(): void
    {
        $this->httpClient = $this->getMockBuilder(Client::class)->addMethods(['post'])->getMock();
        $this->serializerFactory = $this->createMock(SerializerFactoryInterface::class);
        $this->ingApiClient = new IngApiClient($this->httpClient, $this->serializerFactory, self::TOKEN, self::URL);
    }

    public function testCreateTransaction(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $transactionModel = $this->createMock(TransactionModelInterface::class);

        $parameters['body'] = 'model';

        $parameters['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer token',
        ];

        $this->serializerFactory
            ->expects($this->once())
            ->method('createSerializerWithNormalizer')
            ->willReturn($serializer);

        $serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($transactionModel, 'json')
            ->willReturn('model');

        $this->httpClient
            ->expects($this->once())
            ->method('post')
            ->with('url/transaction', $parameters)
            ->willReturn(new Response());

        $result = $this->ingApiClient->createTransaction($transactionModel);

        self::assertEquals($result, new Response());
    }

    public function testCreateTransactionWithException(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $transactionModel = $this->createMock(TransactionModelInterface::class);
        $exception = $this->createMock(GuzzleException::class);

        $parameters['body'] = 'model';
        $parameters['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer token',
        ];

        $this->serializerFactory
            ->expects($this->once())
            ->method('createSerializerWithNormalizer')
            ->willReturn($serializer);

        $serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($transactionModel, 'json')
            ->willReturn('model');

        $this->httpClient
            ->expects($this->once())
            ->method('post')
            ->with('url/transaction', $parameters)
            ->will($this->throwException($exception));

        $this->expectException(IngBadRequestException::class);

        $this->ingApiClient->createTransaction($transactionModel);
    }
}
