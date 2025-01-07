<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Command\TakeOverPayment;
use BitBag\SyliusImojePlugin\Repository\PaymentMethodRepositoryInterface;
use BitBag\SyliusImojePlugin\Resolver\PaymentMethod\PaymentMethodResolver;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class TakeOverPaymentHandler
{
    private PaymentMethodRepositoryInterface $paymentMethodRepository;
    private PaymentMethodResolver $paymentMethodResolver;
    private RepositoryInterface $paymentRepository;

    public function __construct(
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        PaymentMethodResolver $paymentMethodResolver,
        RepositoryInterface $paymentRepository
    ) {
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentMethodResolver = $paymentMethodResolver;
        $this->paymentRepository = $paymentRepository;
    }

    public function __invoke(TakeOverPayment $command): void
    {
        $payment = $command->getPayment();
        $paymentMethod = $this->paymentMethodResolver->resolve($payment);

        if ($paymentMethod->getCode() === $command->getPaymentCode()) {
            return;
        }

        $paymentMethod = $this->paymentMethodRepository->findOneForImojeCode($command->getPaymentCode());

        if (!$paymentMethod) {
            throw new \InvalidArgumentException(sprintf('Payment method with code "%s" not found.', $command->getPaymentCode()));
        }

        $payment->setMethod($paymentMethod);
        $this->paymentRepository->add($payment);
    }
}
