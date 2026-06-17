<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Bus\Handler;

use BitBag\SyliusIngPayPlugin\Bus\Command\TakeOverPayment;
use BitBag\SyliusIngPayPlugin\Repository\PaymentMethodRepositoryInterface;
use BitBag\SyliusIngPayPlugin\Resolver\PaymentMethod\PaymentMethodResolver;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class TakeOverPaymentHandler implements MessageHandlerInterface
{
    private PaymentMethodRepositoryInterface $paymentMethodRepository;

    private PaymentMethodResolver $paymentMethodResolver;

    private RepositoryInterface $paymentRepository;

    public function __construct(
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        PaymentMethodResolver $paymentMethodResolver,
        RepositoryInterface $paymentRepository,
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

        $paymentMethod = $this->paymentMethodRepository->findOneForIngPayCode($command->getPaymentCode());
        $payment->setMethod($paymentMethod);

        $this->paymentRepository->add($payment);
    }
}
