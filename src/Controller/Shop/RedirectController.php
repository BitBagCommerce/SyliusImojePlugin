<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop;

use BitBag\SyliusIngPlugin\Bus\Command\FinalizeOrder;
use BitBag\SyliusIngPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPlugin\Bus\Query\GetResponseData;
use BitBag\SyliusIngPlugin\Factory\Bus\PaymentFinalizationCommandFactoryInterface;
use BitBag\SyliusIngPlugin\Model\ReadyTransaction\ReadyTransactionModelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RedirectController
{
    private DispatcherInterface $dispatcher;

    private PaymentFinalizationCommandFactoryInterface $commandFactory;

    public function __construct(DispatcherInterface $dispatcher, PaymentFinalizationCommandFactoryInterface $commandFactory)
    {
        $this->dispatcher = $dispatcher;
        $this->commandFactory = $commandFactory;
    }

    public function __invoke(Request $request, string $status, int $paymentId): Response
    {
        /** @var ReadyTransactionModelInterface $readyTransaction */
        $readyTransaction = $this->dispatcher->dispatch(new GetResponseData($paymentId));

        $payment = $readyTransaction->getIngTransaction()->getPayment();

        $this->dispatcher->dispatch(new FinalizeOrder($readyTransaction->getOrder()));

        $this->dispatcher->dispatch(
            $this->commandFactory->createNew($readyTransaction->getStatus(), $payment)
        );

        return new Response();
    }
}
