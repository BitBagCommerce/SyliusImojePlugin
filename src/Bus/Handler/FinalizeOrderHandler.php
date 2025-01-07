<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Command\FinalizeOrder;
use SM\Factory\FactoryInterface;
use Sylius\Bundle\ApiBundle\Command\Checkout\SendOrderConfirmation;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderCheckoutTransitions;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class FinalizeOrderHandler
{
    private const CART_STATE = 'cart';

    private FactoryInterface $stateMachineFactory;
    private RepositoryInterface $orderRepository;
    private MessageBusInterface $commandBus;

    public function __construct(
        FactoryInterface $stateMachineFactory,
        RepositoryInterface $orderRepository,
        MessageBusInterface $commandBus
    ) {
        $this->stateMachineFactory = $stateMachineFactory;
        $this->orderRepository = $orderRepository;
        $this->commandBus = $commandBus;
    }

    public function __invoke(FinalizeOrder $command): void
    {
        $order = $command->getOrder();
        $state = $order->getState();
        $stateMachine = $this->stateMachineFactory->get($order, OrderCheckoutTransitions::GRAPH);
        $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_COMPLETE, true);
        $this->orderRepository->add($order);

        $this->sendOrderConfirmationEmail($order, $state);
    }

    private function sendOrderConfirmationEmail(OrderInterface $order, string $state): void
    {
        $token = $order->getTokenValue();
        if ($token && $state === self::CART_STATE) {
            $this->commandBus->dispatch(new SendOrderConfirmation($token));
        }
    }
}
