<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Controller\Shop\Webhook;

use BitBag\SyliusIngPayPlugin\Model\Status\StatusResponseModelInterface;
use BitBag\SyliusIngPayPlugin\Processor\Webhook\Status\WebhookResponseProcessorInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Payment\IngPayTransactionPaymentResolverInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Webhook\oneClickWebhookResolverInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Webhook\WebhookResolverInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class WebhookController
{
    public const SIGNATURE_HEADER_NAME = 'X-IngPay-Signature';

    private IngPayTransactionPaymentResolverInterface $ingPayTransactionPaymentResolver;

    private WebhookResolverInterface $webhookResolver;

    private WebhookResponseProcessorInterface $webhookResponseProcessor;

    private oneClickWebhookResolverInterface $oneClickWebhookResolver;

    public function __construct(
        IngPayTransactionPaymentResolverInterface $ingPayTransactionPaymentResolver,
        WebhookResolverInterface $webhookResolver,
        WebhookResponseProcessorInterface $webhookResponseProcessor,
        oneClickWebhookResolverInterface $oneClickWebhookResolver,
    ) {
        $this->ingPayTransactionPaymentResolver = $ingPayTransactionPaymentResolver;
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
        $payment = $this->ingPayTransactionPaymentResolver->resolve($webhookModel->getTransactionId());

        $this->webhookResponseProcessor->process($webhookModel, $payment);

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }
}
