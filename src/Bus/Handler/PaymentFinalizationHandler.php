<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Command\Status\PaymentFinalizationCommandInterface;
use SM\Factory\FactoryInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class PaymentFinalizationHandler
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

        if (!$payment) {
            throw new \InvalidArgumentException('Payment cannot be null.');
        }

        $stateMachine = $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH);

        if (!$stateMachine->can($command->getPaymentTransitionName())) {
            throw new \LogicException(sprintf(
                'Transition "%s" cannot be applied to the payment in its current state.',
                $command->getPaymentTransitionName()
            ));
        }

        $stateMachine->apply($command->getPaymentTransitionName(), true);

        $this->paymentRepository->add($payment);
    }
}
