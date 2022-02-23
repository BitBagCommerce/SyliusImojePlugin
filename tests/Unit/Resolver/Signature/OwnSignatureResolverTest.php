<?php

declare(strict_types=1);

namespace Unit\Resolver\Signature;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Resolver\GatewayCode\GatewayCodeResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\Signature\OwnSignatureResolver;
use BitBag\SyliusIngPlugin\Resolver\Signature\OwnSignatureResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\TransactionId\TransactionIdResolverInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class OwnSignatureResolverTest extends TestCase
{
    protected RequestStack $requestStack;

    protected GatewayCodeResolverInterface $gatewayCodeResolver;

    protected TransactionIdResolverInterface $transactionIdResolver;

    protected IngClientConfigurationProviderInterface $configurationProvider;

    protected OwnSignatureResolverInterface $ownSignatureResolver;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->gatewayCodeResolver = $this->createMock(GatewayCodeResolverInterface::class);
        $this->transactionIdResolver = $this->createMock(TransactionIdResolverInterface::class);
        $this->configurationProvider = $this->createMock(IngClientConfigurationProviderInterface::class);
        $this->ownSignatureResolver = new OwnSignatureResolver(
            $this->requestStack,
            $this->gatewayCodeResolver,
            $this->transactionIdResolver,
            $this->configurationProvider
        );
    }

    public function testResolveSignature(): void
    {
        $requestMock = $this->createMock(Request::class);
        $config = $this->createMock(IngClientConfigurationInterface::class);

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($requestMock);

        $requestMock
            ->method('getContent')
            ->willReturn('content');

        $this->transactionIdResolver
            ->method('resolve')
            ->with('content')
            ->willReturn('transactionId');

        $this->gatewayCodeResolver
            ->method('resolve')
            ->with('transactionId')
            ->willReturn('ing_code');

        $this->configurationProvider
            ->method('getPaymentMethodConfiguration')
            ->with('ing_code')
            ->willReturn($config);

        $config
            ->method('getShopKey')
            ->willReturn('ShopKey');

        $result = $this->ownSignatureResolver->resolve();
        self::assertEquals('contentShopKey', $result);
    }
}
