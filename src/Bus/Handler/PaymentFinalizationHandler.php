<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Command\Status\PaymentFinalizationCommandInterface;
use SM\Factory\FactoryInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PaymentFinalizationHandler implements MessageHandlerInterface
{
    private FactoryInterface $stateMachineFactory;

    private RepositoryInterface $paymentRepository;

    public function __construct(
        FactoryInterface $stateMachineFactory,
        RepositoryInterface $paymentRepository
    ) {
        $this->stateMachineFactory = $stateMachineFactory;
        $this->paymentRepository = $paymentRepository;
    }

    public function __invoke(PaymentFinalizationCommandInterface $command): void
    {
        $payment = $command->getPayment();

        $stateMachine = $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH);
        $stateMachine->apply($command->getPaymentTransitionName(), true);

        $this->paymentRepository->add($payment);
    }
}
