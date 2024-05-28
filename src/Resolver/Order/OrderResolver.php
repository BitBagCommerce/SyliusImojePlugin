<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Order;

use BitBag\SyliusImojePlugin\Exception\MissingOrderException;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;

final class OrderResolver implements OrderResolverInterface
{
    private CartContextInterface $cartContext;

    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        CartContextInterface $cartContext,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->cartContext = $cartContext;
        $this->orderRepository = $orderRepository;
    }

    public function resolve(?string $orderId = null): OrderInterface
    {
        $order = null;

        if (null !== $orderId) {
            /** @var OrderInterface|null $order */
            $order = $this->orderRepository->find($orderId);
        }

        if (!$order instanceof OrderInterface) {
            try {
                $order = $this->cartContext->getCart();
            } catch (CartNotFoundException $e) {
                throw new MissingOrderException($e->getMessage());
            }
        }

        if ($order instanceof OrderInterface) {
            return $order;
        }

        throw new MissingOrderException('Order could not be identified from request data.');
    }
}
