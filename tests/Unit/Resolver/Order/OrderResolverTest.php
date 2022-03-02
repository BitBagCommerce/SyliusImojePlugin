<?php

declare(strict_types=1);

namespace Unit\Resolver\Order;

use BitBag\SyliusIngPlugin\Exception\MissingOrderException;
use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolver;
use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class OrderResolverTest extends TestCase
{
    private const TOKEN_VALUE = '12345';

    /** @var CartContextInterface */
    protected $context;

    /** @var RepositoryInterface */
    protected $repository;

    /** @var OrderResolverInterface */
    protected $resolver;

    protected function setUp(): void
    {
        $this->context = $this->createMock(CartContextInterface::class);
        $this->repository = $this->createMock(OrderRepositoryInterface::class);

        $this->resolver = new OrderResolver(
            $this->context,
            $this->repository
        );
    }

    public function testFindInRepository(): void
    {
        $this->repository
            ->method('find')
            ->willReturn(new Order());

        $order = $this->resolver->resolve('123');

        self::assertNotNull($order);
    }

    public function testFindFromCart(): void
    {
        $this->context
            ->method('getCart')
            ->willReturn(new Order());

        $order = $this->resolver->resolve();

        self::assertNotNull($order);
    }

    public function testOrderNotFoundInCart(): void
    {
        $this->expectException(MissingOrderException::class);

        $this->context
            ->method('getCart')
            ->willThrowException(new CartNotFoundException());

        $this->resolver->resolve(self::TOKEN_VALUE);
    }
}
