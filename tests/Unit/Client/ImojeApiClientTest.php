<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusImojePlugin\Unit\Client;

use BitBag\SyliusImojePlugin\Client\ImojeApiClient;
use BitBag\SyliusImojePlugin\Exception\ImojeBadRequestException;
use BitBag\SyliusImojePlugin\Model\TransactionModelInterface;
use BitBag\SyliusImojePlugin\Provider\RequestParams\RequestParamsProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;

final class ImojeApiClientTest extends TestCase
{
    private Client $httpClient;

    private RequestParamsProviderInterface $requestParamsProvider;

    private Serializer $serializer;

    private ImojeApiClient $imojeApiClient;

    public const TOKEN = 'token';

    public const URL = 'url/';

    protected function setUp(): void
    {
        // I'm using a mockBuilder because these methods are added via annotations
        $this->httpClient = $this->getMockBuilder(Client::class)->addMethods(['post'])->getMock();
        $this->requestParamsProvider = $this->createMock(RequestParamsProviderInterface::class);
        $this->serializer = $this->createMock(Serializer::class);
        $this->imojeApiClient = new ImojeApiClient(
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

        $result = $this->imojeApiClient->createTransaction($transactionModel);

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

        $this->expectException(ImojeBadRequestException::class);

        $this->imojeApiClient->createTransaction($transactionModel);
    }
}
