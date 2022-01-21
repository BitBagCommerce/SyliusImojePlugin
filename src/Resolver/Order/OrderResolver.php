<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Order;

use BitBag\SyliusIngPlugin\Exception\MissingOrderException;
use BitBag\SyliusIngPlugin\Exception\MissingRequestException;
use BitBag\SyliusIngPlugin\Repository\Order\OrderRepositoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class OrderResolver implements OrderResolverInterface
{
    private RequestStack $requestStack;

    private CartContextInterface $cartContext;

    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        RequestStack $requestStack,
        CartContextInterface $cartContext,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->requestStack = $requestStack;
        $this->cartContext = $cartContext;
        $this->orderRepository = $orderRepository;
    }

    private function getCurrentRequest(): Request
    {
        $result = $this->requestStack->getMasterRequest();

        if ($result === null) {
            throw new MissingRequestException();
        }

        return $result;
    }

    private function getCurrentOrder(): ?OrderInterface
    {
        $request = $this->getCurrentRequest();
        /**
         * @var string|null $tokenValue
         */
        $tokenValue = $request->get('sylius_checkout_complete')['_token'];
        if (null === $tokenValue) {
            return null;
        }

        return $this->orderRepository->findByTokenValue($tokenValue);
    }

    public function resolve(?string $tokenValue = null): OrderInterface
    {
        $order = null;

        if ($tokenValue !== null) {
            $order = $this->orderRepository->findByTokenValue($tokenValue);
        }

        if (!$order instanceof OrderInterface) {
            $order = $this->getCurrentOrder();
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
