<?php

declare(strict_types=1);

namespace Unit\Resolver\Url;

use BitBag\SyliusIngPlugin\Client\IngApiClientInterface;
use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;
use BitBag\SyliusIngPlugin\Resolver\Url\UrlResolver;
use BitBag\SyliusIngPlugin\Resolver\Url\UrlResolverInterface;
use PHPUnit\Framework\TestCase;

final class UrlResolverTest extends TestCase
{
    protected const GATEWAY_CODE = 'ing_code';

    protected const SANDBOX_URL = 'http://sandbox';

    protected const PROD_URL = 'http://prod';

    protected const MERCHANT_ID = 'MerchantId';

    protected const TRANSACTION_ID = 'TR-12345';

    protected const TRANSACTION_ENDPOINT = 'transaction';

    protected const COMPLETE_SANDBOX_URL = 'http://sandbox/MerchantId/transaction/TR-12345';

    protected const COMPLETE_PROD_URL = 'http://prod/MerchantId/transaction/TR-12345';

    protected IngTransactionInterface $ingTransaction;

    protected IngClientConfigurationProviderInterface $ingClientConfiguration;

    protected IngClientProviderInterface $ingClientProvide;

    protected UrlResolverInterface $urlResolver;

    protected function setUp(): void
    {
        $this->ingTransaction = $this->createMock(IngTransactionInterface::class);
        $this->ingClientConfiguration = $this->createMock(IngClientConfigurationProviderInterface::class);
        $this->ingClientProvide = $this->createMock(IngClientProviderInterface::class);
        $this->urlResolver = new UrlResolver();
    }

    /**
     * @dataProvider dataToUrlProvider
     */
    public function testResolveUrl(bool $isProd,string $url,string $result): void
    {
        $configuration = $this->createMock(IngClientConfigurationInterface::class);
        $client = $this->createMock(IngApiClientInterface::class);

        $this->ingTransaction
            ->method('getGatewayCode')
            ->willReturn(self::GATEWAY_CODE);

        $this->ingClientConfiguration
            ->method('getPaymentMethodConfiguration')
            ->with(self::GATEWAY_CODE)
            ->willReturn($configuration);

        $this->ingClientProvide
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

        $this->ingTransaction
            ->method('getTransactionId')
            ->willReturn(self::TRANSACTION_ID);

        self::assertEquals(
            $result,
            $this->urlResolver->resolve($this->ingTransaction,$this->ingClientConfiguration,$this->ingClientProvide)
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
