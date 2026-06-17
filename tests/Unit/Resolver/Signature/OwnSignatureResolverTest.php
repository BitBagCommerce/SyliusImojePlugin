<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPayPlugin\Unit\Resolver\Signature;

use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientConfigurationProviderInterface;
use BitBag\SyliusIngPayPlugin\Resolver\GatewayCode\GatewayCodeResolverInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Signature\OwnSignatureResolver;
use BitBag\SyliusIngPayPlugin\Resolver\Signature\OwnSignatureResolverInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class OwnSignatureResolverTest extends TestCase
{
    private const PRIVATE_KEY = 'ShopKey';

    private const FACTORY_NAME = 'BitBag_ing_pay';

    private RequestStack $requestStack;

    private GatewayCodeResolverInterface $gatewayCodeResolver;

    private IngPayClientConfigurationProviderInterface $configurationProvider;

    private OwnSignatureResolverInterface $ownSignatureResolver;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->gatewayCodeResolver = $this->createMock(GatewayCodeResolverInterface::class);
        $this->configurationProvider = $this->createMock(IngPayClientConfigurationProviderInterface::class);
        $this->ownSignatureResolver = new OwnSignatureResolver(
            $this->requestStack,
            $this->gatewayCodeResolver,
            $this->configurationProvider,
        );
    }

    public function testResolveSignature(): void
    {
        $requestMock = $this->createMock(Request::class);
        $config = $this->createMock(IngPayClientConfigurationInterface::class);

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($requestMock);

        $requestMock
            ->method('getContent')
            ->willReturn('content');

        $this->gatewayCodeResolver
            ->method('resolve')
            ->with(self::FACTORY_NAME)
            ->willReturn('ing_pay_code');

        $this->configurationProvider
            ->method('getPaymentMethodConfiguration')
            ->with('ing_pay_code')
            ->willReturn($config);

        $config
            ->method('getShopKey')
            ->willReturn(self::PRIVATE_KEY);

        $result = $this->ownSignatureResolver->resolve();

        self::assertEquals('contentShopKey', $result);
    }
}
