<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\TotalResolver;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class TotalResolver implements TotalResolverInterface
{
    private CartContextInterface $cartContext;

    private RequestStack $requestStack;

    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        CartContextInterface $cartContext,
        RequestStack $requestStack,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->cartContext = $cartContext;
        $this->requestStack = $requestStack;
        $this->orderRepository = $orderRepository;
    }

    public function resolve(): int
    {
        $cart = $this->cartContext->getCart();
        $request = $this->requestStack->getCurrentRequest();

        $session = $request->getSession();
        $orderId = $session->get('sylius_order_id');

        if ('' !== $orderId && null !== $cart->getId()) {
            return $cart->getTotal();
        }
        if (null !== $orderId) {
            /** @var OrderInterface $order */
            $order = $this->orderRepository->find($orderId);

            return $order->getTotal();
        }

        return 0;
    }
}
