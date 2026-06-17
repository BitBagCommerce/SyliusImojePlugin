<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPayPlugin\Unit\Client;

use BitBag\SyliusIngPayPlugin\Client\IngPayApiClient;
use BitBag\SyliusIngPayPlugin\Exception\IngPayBadRequestException;
use BitBag\SyliusIngPayPlugin\Model\TransactionModelInterface;
use BitBag\SyliusIngPayPlugin\Provider\RequestParams\RequestParamsProviderInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Serializer\Serializer;

final class IngPayApiClientTest extends TestCase
{
    private ClientInterface $httpClient;

    private RequestParamsProviderInterface $requestParamsProvider;

    private Serializer $serializer;

    private IngPayApiClient $ingPayApiClient;

    public const TOKEN = 'token';

    public const URL = 'url/';

    protected function setUp(): void
    {
        // I'm using a mockBuilder because these methods are added via annotations
        $this->httpClient = $this->getMockBuilder(ClientInterface::class)->getMock();
        $this->requestParamsProvider = $this->createMock(RequestParamsProviderInterface::class);
        $this->serializer = $this->createMock(Serializer::class);
        $this->requestFactory = $this->createMock(RequestFactoryInterface::class);
        $this->streamFactory = $this->createMock(StreamFactoryInterface::class);
        $this->ingPayApiClient = new IngPayApiClient(
            $this->httpClient,
            $this->requestParamsProvider,
            $this->serializer,
            self::TOKEN,
            self::URL,
            $this->requestFactory,
            $this->streamFactory,
        );
    }

    public function testCreateTransaction(): void
    {
        $transactionModel = $this->createMock(TransactionModelInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $response = $this->createMock(ResponseInterface::class);

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

        $this->streamFactory
            ->expects(self::once())
            ->method('createStream')
            ->with($parameters['body'])
            ->willReturn($stream);

        $request
            ->expects(self::exactly(3))
            ->method('withHeader')
            ->withConsecutive(
                ['Accept', 'application/json'],
                ['Content-Type', 'application/json'],
                ['Authorization', 'Bearer token'],
            )
            ->willReturnSelf();

        $request
            ->expects(self::once())
            ->method('withBody')
            ->with($stream)
            ->willReturnSelf();

        $this->requestFactory
            ->expects(self::once())
            ->method('createRequest')
            ->with('POST', 'url/transaction')
            ->willReturn($request);

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($request)
            ->willReturn($response);

        $result = $this->ingPayApiClient->createTransaction($transactionModel);

        self::assertEquals(200, $result->getStatusCode());
    }

    public function testCreateTransactionWithException(): void
    {
        $transactionModel = $this->createMock(TransactionModelInterface::class);
        $exception = $this->createMock(ClientExceptionInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $parameters = [
            'body' => 'model',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer token',
            ],
        ];

        $this->requestParamsProvider
            ->expects($this->once())
            ->method('buildRequestParams')
            ->willReturn($parameters);
        $this->streamFactory
            ->expects(self::once())
            ->method('createStream')
            ->with($parameters['body'])
            ->willReturn($stream);

        $request
            ->expects(self::exactly(3))
            ->method('withHeader')
            ->withConsecutive(
                ['Accept', 'application/json'],
                ['Content-Type', 'application/json'],
                ['Authorization', 'Bearer token'],
            )
            ->willReturnSelf();

        $request
            ->expects(self::once())
            ->method('withBody')
            ->with($stream)
            ->willReturnSelf();

        $this->requestFactory
            ->expects(self::once())
            ->method('createRequest')
            ->with('POST', 'url/transaction')
            ->willReturn($request);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($request)
            ->willThrowException($exception);

        $this->expectException(IngPayBadRequestException::class);

        $this->ingPayApiClient->createTransaction($transactionModel);
    }
}
