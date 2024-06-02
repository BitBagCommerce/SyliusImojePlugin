<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusImojePlugin\Unit\Resolver\Url;

use BitBag\SyliusImojePlugin\Client\ImojeApiClientInterface;
use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;
use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientProviderInterface;
use BitBag\SyliusImojePlugin\Resolver\Url\UrlResolver;
use BitBag\SyliusImojePlugin\Resolver\Url\UrlResolverInterface;
use PHPUnit\Framework\TestCase;

final class UrlResolverTest extends TestCase
{
    public const GATEWAY_CODE = 'imoje_code';

    public const SANDBOX_URL = 'http://sandbox';

    public const PROD_URL = 'http://prod';

    public const MERCHANT_ID = 'MerchantId';

    public const TRANSACTION_ID = 'TR-12345';

    public const TRANSACTION_ENDPOINT = 'transaction';

    public const COMPLETE_SANDBOX_URL = 'http://sandbox/MerchantId/transaction/TR-12345';

    public const COMPLETE_PROD_URL = 'http://prod/MerchantId/transaction/TR-12345';

    private ImojeTransactionInterface $imojeTransaction;

    private ImojeClientConfigurationProviderInterface $imojeClientConfiguration;

    private ImojeClientProviderInterface $imojeClientProvider;

    private UrlResolverInterface $urlResolver;

    protected function setUp(): void
    {
        $this->imojeTransaction = $this->createMock(ImojeTransactionInterface::class);
        $this->imojeClientConfiguration = $this->createMock(ImojeClientConfigurationProviderInterface::class);
        $this->imojeClientProvider = $this->createMock(ImojeClientProviderInterface::class);
        $this->urlResolver = new UrlResolver();
    }

    /**
     * @dataProvider dataToUrlProvider
     */
    public function testResolveUrl(bool $isProd,string $url,string $result): void
    {
        $configuration = $this->createMock(ImojeClientConfigurationInterface::class);
        $client = $this->createMock(ImojeApiClientInterface::class);

        $this->imojeTransaction
            ->method('getGatewayCode')
            ->willReturn(self::GATEWAY_CODE);

        $this->imojeClientConfiguration
            ->method('getPaymentMethodConfiguration')
            ->with(self::GATEWAY_CODE)
            ->willReturn($configuration);

        $this->imojeClientProvider
            ->method('getClient')
            ->with(self::GATEWAY_CODE)
            ->willReturn($client);

        $configuration
            ->method('isProd')
            ->willReturn($isProd);

        $configuration
            ->method('getSandboxUrl')
            ->willReturn($url);

        $configuration
            ->method('getProdUrl')
            ->willReturn($url);

        $configuration
            ->method('getMerchantId')
            ->willReturn(self::MERCHANT_ID);

        $this->imojeTransaction
            ->method('getTransactionId')
            ->willReturn(self::TRANSACTION_ID);

        self::assertEquals(
            $result,
            $this->urlResolver->resolve($this->imojeTransaction,$this->imojeClientConfiguration,$this->imojeClientProvider)
        );
    }

    public function dataToUrlProvider()
    {
        return [
            [true, self::PROD_URL, self::COMPLETE_PROD_URL],
            [false, self::SANDBOX_URL, self::COMPLETE_SANDBOX_URL],
        ];
    }
}
