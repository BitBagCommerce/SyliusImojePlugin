<?php

declare(strict_types=1);

namespace Unit\Provider;

use BitBag\SyliusIngPlugin\Client\IngApiClientInterface;
use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Factory\Serializer\SerializerFactoryInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientProvider;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;
use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class IngClientProviderTest extends TestCase
{
    /** @var IngClientConfigurationProviderInterface|MockObject */
    private object $ingClientConfigurationProvider;

    /** @var Client|MockObject */
    private object $httpClient;

    /** @var SerializerFactoryInterface|MockObject */
    private object $serializerFactory;

    private IngClientProviderInterface $ingClientProvider;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(Client::class);
        $this->serializerFactory = $this->createMock(SerializerFactoryInterface::class);
        $this->ingClientConfigurationProvider = $this->createMock(
            IngClientConfigurationProviderInterface::class
        );
        $this->ingClientProvider = new IngClientProvider(
            $this->ingClientConfigurationProvider,
            $this->httpClient,
            $this->serializerFactory
        );
    }

    /**
     * @dataProvider provider
     */
    public function test_return_client(bool $isProd, string $method, string $url): void
    {
        $configuration = $this->createMock(IngClientConfigurationInterface::class);

        $this->ingClientConfigurationProvider
            ->expects($this->once())
            ->method('getPaymentMethodConfiguration')
            ->with('code')
            ->willReturn($configuration);

        $configuration
            ->expects($this->once())
            ->method('getToken')
            ->willReturn('token');

        $configuration
            ->expects($this->once())
            ->method('getMerchantId')
            ->willReturn('MerchantId');

        $configuration
            ->expects($this->once())
            ->method('isProd')
            ->willReturn($isProd);

        $configuration
            ->expects($this->once())
            ->method($method)
            ->willReturn($url);

        self::assertInstanceOf(IngApiClientInterface::class, $this->ingClientProvider->getClient('code'));
    }

    public function provider()
    {
        return [
            [true, 'getProdUrl', 'production'],
            [false, 'getSandboxUrl', 'sandbox'],
        ];
    }
}
