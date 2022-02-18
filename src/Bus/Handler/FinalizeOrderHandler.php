<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Handler;

use BitBag\SyliusIngPlugin\Bus\Command\FinalizeOrder;
use SM\Factory\FactoryInterface;
use Sylius\Component\Core\OrderCheckoutTransitions;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FinalizeOrderHandler implements MessageHandlerInterface
{
    private FactoryInterface $stateMachineFactory;

    private RepositoryInterface $orderRepository;

    public function __construct(
        FactoryInterface $stateMachineFactory,
        RepositoryInterface $orderRepository
    ) {
        $this->stateMachineFactory = $stateMachineFactory;
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(FinalizeOrder $command): void
    {
        $order = $command->getOrder();

        $stateMachine = $this->stateMachineFactory->get($order, OrderCheckoutTransitions::GRAPH);
        $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_COMPLETE, true);

        $this->orderRepository->add($order);
    }
}
