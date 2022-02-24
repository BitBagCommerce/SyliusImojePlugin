<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop\Webhook;

use BitBag\SyliusIngPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPlugin\Resolver\Payment\IngTransactionPaymentResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class WebhookController
{
    public const SIGNATURE_HEADER_NAME = 'X-Imoje-Signature';

    private DispatcherInterface $dispatcher;

    private IngTransactionPaymentResolverInterface $ingTransactionPaymentResolver;

    public function __construct(
        DispatcherInterface $dispatcher,
        IngTransactionPaymentResolverInterface $ingTransactionPaymentResolver
    ) {
        $this->dispatcher = $dispatcher;
        $this->ingTransactionPaymentResolver = $ingTransactionPaymentResolver;
    }

    public function __invoke(Request $request): Response
    {
        return new Response();
    }
}
