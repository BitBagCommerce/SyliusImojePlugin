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
use Symfony\Component\Serializer\SerializerInterface;

final class IngApiClientTest extends TestCase
{
    /** @var Client|MockObject */
    private object $httpClient;

    /** @var SerializerFactoryInterface|MockObject */
    private object $serializerFactory;

    private IngApiClient $ingApiClient;

    protected function setUp(): void
    {
        $this->httpClient = $this->getMockBuilder(Client::class)->addMethods(['post'])->getMock();
        $this->serializerFactory = $this->createMock(SerializerFactoryInterface::class);
        $token = "token";
        $url = "url/";
        $this->ingApiClient = new IngApiClient( $this->httpClient, $this->serializerFactory, $token,$url);
    }

    public function test_create_transaction(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $transactionModel = $this->createMock(TransactionModelInterface::class);

        $parameters['body'] = 'model';
        $parameters['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer token'
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

        $this->ingApiClient->createTransaction($transactionModel);
    }

    public function test_create_transaction_with_Exception(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $transactionModel = $this->createMock(TransactionModelInterface::class);
        $exception = $this->createMock(GuzzleException::class);

        $parameters['body'] = 'model';
        $parameters['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer token'
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
