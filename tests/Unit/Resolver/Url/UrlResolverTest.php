<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPayPlugin\Unit\Resolver\Url;

use BitBag\SyliusIngPayPlugin\Client\IngPayApiClientInterface;
use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;
use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientConfigurationProviderInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientProviderInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Url\UrlResolver;
use BitBag\SyliusIngPayPlugin\Resolver\Url\UrlResolverInterface;
use PHPUnit\Framework\TestCase;

final class UrlResolverTest extends TestCase
{
    public const GATEWAY_CODE = 'ing_pay_code';

    public const SANDBOX_URL = 'http://sandbox';

    public const PROD_URL = 'http://prod';

    public const MERCHANT_ID = 'MerchantId';

    public const TRANSACTION_ID = 'TR-12345';

    public const TRANSACTION_ENDPOINT = 'transaction';

    public const COMPLETE_SANDBOX_URL = 'http://sandbox/MerchantId/transaction/TR-12345';

    public const COMPLETE_PROD_URL = 'http://prod/MerchantId/transaction/TR-12345';

    private IngPayTransactionInterface $ingPayTransaction;

    private IngPayClientConfigurationProviderInterface $ingPayClientConfiguration;

    private IngPayClientProviderInterface $ingPayClientProvider;

    private UrlResolverInterface $urlResolver;

    protected function setUp(): void
    {
        $this->ingPayTransaction = $this->createMock(IngPayTransactionInterface::class);
        $this->ingPayClientConfiguration = $this->createMock(IngPayClientConfigurationProviderInterface::class);
        $this->ingPayClientProvider = $this->createMock(IngPayClientProviderInterface::class);
        $this->urlResolver = new UrlResolver();
    }

    /**
     * @dataProvider dataToUrlProvider
     */
    public function testResolveUrl(bool $isProd, string $url, string $result): void
    {
        $configuration = $this->createMock(IngPayClientConfigurationInterface::class);
        $client = $this->createMock(IngPayApiClientInterface::class);

        $this->ingPayTransaction
            ->method('getGatewayCode')
            ->willReturn(self::GATEWAY_CODE);

        $this->ingPayClientConfiguration
            ->method('getPaymentMethodConfiguration')
            ->with(self::GATEWAY_CODE)
            ->willReturn($configuration);

        $this->ingPayClientProvider
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

        $this->ingPayTransaction
            ->method('getTransactionId')
            ->willReturn(self::TRANSACTION_ID);

        self::assertEquals(
            $result,
            $this->urlResolver->resolve($this->ingPayTransaction, $this->ingPayClientConfiguration, $this->ingPayClientProvider),
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
