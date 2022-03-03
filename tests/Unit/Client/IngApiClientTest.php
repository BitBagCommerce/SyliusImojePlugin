<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPlugin\Unit\Client;

use BitBag\SyliusIngPlugin\Client\IngApiClient;
use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
use BitBag\SyliusIngPlugin\Provider\RequestParams\RequestParamsProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;

final class IngApiClientTest extends TestCase
{
    private Client $httpClient;

    private RequestParamsProviderInterface $requestParamsProvider;

    private SerializerInterface $serializer;

    private IngApiClient $ingApiClient;

    public const TOKEN = 'token';

    public const URL = 'url/';

    protected function setUp(): void
    {
        // I'm using a mockBuilder because these methods are added via annotations
        $this->httpClient = $this->getMockBuilder(Client::class)->addMethods(['post'])->getMock();
        $this->requestParamsProvider = $this->createMock(RequestParamsProviderInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->ingApiClient = new IngApiClient(
            $this->httpClient,
            $this->requestParamsProvider,
            $this->serializer,
            self::TOKEN,
            self::URL
        );
    }

    public function testCreateTransaction(): void
    {
        $transactionModel = $this->createMock(TransactionModelInterface::class);

        $parameters['body'] = 'model';

        $parameters['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer token',
        ];

        $this->requestParamsProvider
            ->expects($this->once())
            ->method('buildRequestParams')
            ->willReturn($parameters);

        $this->httpClient
            ->expects($this->once())
            ->method('post')
            ->with('url/transaction', $parameters)
            ->willReturn(new Response());

        $result = $this->ingApiClient->createTransaction($transactionModel);

        self::assertEquals($result->getStatusCode(), 200);
    }

    public function testCreateTransactionWithException(): void
    {
        $transactionModel = $this->createMock(TransactionModelInterface::class);
        $exception = $this->createMock(GuzzleException::class);

        $parameters['body'] = 'model';
        $parameters['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer token',
        ];

        $this->requestParamsProvider
            ->expects($this->once())
            ->method('buildRequestParams')
            ->willReturn($parameters);

        $this->httpClient
            ->expects($this->once())
            ->method('post')
            ->with('url/transaction', $parameters)
            ->will($this->throwException($exception));

        $this->expectException(IngBadRequestException::class);

        $this->ingApiClient->createTransaction($transactionModel);
    }
}
