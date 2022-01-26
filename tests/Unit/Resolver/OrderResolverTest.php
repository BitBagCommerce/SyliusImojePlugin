<?php

declare(strict_types=1);

namespace Unit\Resolver;

use BitBag\SyliusIngPlugin\Exception\MissingOrderException;
use BitBag\SyliusIngPlugin\Exception\MissingRequestException;
use BitBag\SyliusIngPlugin\Repository\Order\OrderRepositoryInterface;
use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolver;
use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolverInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class OrderResolverTest extends TestCase
{
    /** @var RequestStack|MockObject */
    private object $requestStack;

    /** @var CartContextInterface|MockObject */
    private object $cartContext;

    /** @var OrderRepositoryInterface|MockObject */
    private object $orderRepository;

    private OrderResolverInterface $orderResolver;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->cartContext = $this->createMock(CartContextInterface::class);
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->orderResolver = new OrderResolver($this->requestStack,$this->cartContext,$this->orderRepository);
    }

    /**
     * @dataProvider provider
     */
    public function test_return_order($order, $valueFromRequest): void
    {
        $orderToCart = $this->createMock(OrderInterface::class);
        $request = $this->createMock(Request::class);

        $this->orderRepository
            ->method('findByTokenValue')
            ->with('tokenValue')
            ->willReturn($order);

        $this->requestStack
            ->method('getMasterRequest')
            ->willReturn($request);

        $request
            ->method('get')
            ->with('tokenValue')
            ->willReturn($valueFromRequest);

        $this->cartContext
            ->method('getCart')
            ->willReturn($orderToCart);

        $this->assertInstanceOf(OrderInterface::class, $this->orderResolver->resolve('tokenValue'));
    }

    public function test_return_request_exception(): void
    {
        $this->orderRepository
            ->method('findByTokenValue')
            ->with('tokenValue')
            ->willReturn(null);

        $this->requestStack
            ->method('getMasterRequest')
            ->willReturn(null);

        $this->expectException(MissingRequestException::class);

        $this->assertInstanceOf(OrderInterface::class, $this->orderResolver->resolve('tokenValue'));
    }

    public function test_return_cart_not_found_exception(): void
    {
        $request = $this->createMock(Request::class);

        $this->orderRepository
            ->method('findByTokenValue')
            ->with('tokenValue')
            ->willReturn(null);

        $this->requestStack
            ->method('getMasterRequest')
            ->willReturn($request);

        $request
            ->method('get')
            ->with('tokenValue')
            ->willReturn('tokenValue');

        $this->cartContext
            ->method('getCart')
            ->willThrowException(new CartNotFoundException());

        $this->expectException(MissingOrderException::class);
        $this->expectException(MissingOrderException::class);
        $this->assertInstanceOf(OrderInterface::class, $this->orderResolver->resolve('tokenValue'));
    }

    public function provider(): array
    {
        return [
            [$this->createMock(OrderInterface::class), 'tokenValue'],
            [null,null],
            [null, 'tokenValue']
        ];
    }
}
