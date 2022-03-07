<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop\Webhook;

use BitBag\SyliusIngPlugin\Model\Status\StatusResponseModelInterface;
use BitBag\SyliusIngPlugin\Processor\Webhook\Status\WebhookResponseProcessorInterface;
use BitBag\SyliusIngPlugin\Resolver\Payment\IngTransactionPaymentResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\Webhook\oneClickWebhookResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\Webhook\WebhookResolverInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class WebhookController
{
    public const SIGNATURE_HEADER_NAME = 'X-Imoje-Signature';

    private IngTransactionPaymentResolverInterface $ingTransactionPaymentResolver;

    private WebhookResolverInterface $webhookResolver;

    private WebhookResponseProcessorInterface $webhookResponseProcessor;

    private oneClickWebhookResolverInterface $oneClickWebhookResolver;

    public function __construct(
        IngTransactionPaymentResolverInterface $ingTransactionPaymentResolver,
        WebhookResolverInterface $webhookResolver,
        WebhookResponseProcessorInterface $webhookResponseProcessor,
        oneClickWebhookResolverInterface $oneClickWebhookResolver
    ) {
        $this->ingTransactionPaymentResolver = $ingTransactionPaymentResolver;
        $this->webhookResolver = $webhookResolver;
        $this->webhookResponseProcessor = $webhookResponseProcessor;
        $this->oneClickWebhookResolver = $oneClickWebhookResolver;
    }

    public function __invoke(Request $request): Response
    {
        $isOneClickNotification = $this->oneClickWebhookResolver->resolve();

        if ($isOneClickNotification) {
            return new JsonResponse([
                'status' => 'ok',
            ]);
        }
        /** @var StatusResponseModelInterface $webhookModel */
        $webhookModel = $this->webhookResolver->resolve();
        $payment = $this->ingTransactionPaymentResolver->resolve($webhookModel->getTransactionId());

        $this->webhookResponseProcessor->process($webhookModel, $payment);

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }
}
