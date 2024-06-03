<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusImojePlugin\Unit\Resolver\Signature;

use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Resolver\GatewayCode\GatewayCodeResolverInterface;
use BitBag\SyliusImojePlugin\Resolver\Signature\OwnSignatureResolver;
use BitBag\SyliusImojePlugin\Resolver\Signature\OwnSignatureResolverInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class OwnSignatureResolverTest extends TestCase
{
    private const PRIVATE_KEY = 'ShopKey';

    private const FACTORY_NAME = 'BitBag_imoje';

    private RequestStack $requestStack;

    private GatewayCodeResolverInterface $gatewayCodeResolver;

    private ImojeClientConfigurationProviderInterface $configurationProvider;

    private OwnSignatureResolverInterface $ownSignatureResolver;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->gatewayCodeResolver = $this->createMock(GatewayCodeResolverInterface::class);
        $this->configurationProvider = $this->createMock(ImojeClientConfigurationProviderInterface::class);
        $this->ownSignatureResolver = new OwnSignatureResolver(
            $this->requestStack,
            $this->gatewayCodeResolver,
            $this->configurationProvider
        );
    }

    public function testResolveSignature(): void
    {
        $requestMock = $this->createMock(Request::class);
        $config = $this->createMock(ImojeClientConfigurationInterface::class);

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($requestMock);

        $requestMock
            ->method('getContent')
            ->willReturn('content');

        $this->gatewayCodeResolver
            ->method('resolve')
            ->with(self::FACTORY_NAME)
            ->willReturn('imoje_code');

        $this->configurationProvider
            ->method('getPaymentMethodConfiguration')
            ->with('imoje_code')
            ->willReturn($config);

        $config
            ->method('getShopKey')
            ->willReturn(self::PRIVATE_KEY);

        $result = $this->ownSignatureResolver->resolve();

        self::assertEquals('contentShopKey', $result);
    }
}
