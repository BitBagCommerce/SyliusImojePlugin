<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPlugin\Unit\Resolver\Order;

use BitBag\SyliusIngPlugin\Exception\MissingOrderException;
use BitBag\SyliusIngPlugin\Exception\MissingRequestException;
use BitBag\SyliusIngPlugin\Repository\Order\OrderRepositoryInterface;
use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolver;
use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class OrderResolverTest extends TestCase
{
    private const TOKEN_VALUE = '12345';

    /** @var RequestStack */
    protected $requestStack;

    /** @var CartContextInterface */
    protected $context;

    /** @var RepositoryInterface */
    protected $repository;

    /** @var OrderResolverInterface */
    protected $resolver;

    protected function setUp(): void
    {
        $this->requestStack = new RequestStack();
        $this->context = $this->createMock(CartContextInterface::class);
        $this->repository = $this->createMock(OrderRepositoryInterface::class);

        $this->resolver = new OrderResolver(
            $this->requestStack,
            $this->context,
            $this->repository
        );
    }

    public function testFindFromRequest(): void
    {
        $this->setUpRepository();

        $request = new Request([], [
            'tokenValue' => self::TOKEN_VALUE,
        ]);

        $this->requestStack->push($request);

        $order = $this->resolver->resolve();

        self::assertNotNull($order);
    }

    public function testMissingRequest(): void
    {
        $this->expectException(MissingRequestException::class);

        $this->resolver->resolve();
    }

    public function testFindByProvidedTokenValue(): void
    {
        $this->setUpRepository();

        $this->requestStack->push(new Request());

        $order = $this->resolver->resolve(self::TOKEN_VALUE);

        self::assertNotNull($order);
    }

    public function testFindFromCart(): void
    {
        $this->requestStack->push(new Request());

        $this->context
            ->method('getCart')
            ->willReturn(new Order());

        $order = $this->resolver->resolve();

        self::assertNotNull($order);
    }

    public function testOrderNotFoundInCart(): void
    {
        $this->expectException(MissingOrderException::class);

        $this->requestStack->push(new Request());

        $this->context
            ->method('getCart')
            ->willThrowException(new CartNotFoundException());

        $this->resolver->resolve(self::TOKEN_VALUE);
    }

    private function setUpRepository(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('findByTokenValue')
            ->with(self::TOKEN_VALUE)
            ->willReturn(new Order());
    }
}
