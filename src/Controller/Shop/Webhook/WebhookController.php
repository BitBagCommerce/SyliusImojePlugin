<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Controller\Shop\Webhook;

use BitBag\SyliusImojePlugin\Model\Status\StatusResponseModelInterface;
use BitBag\SyliusImojePlugin\Processor\Webhook\Status\WebhookResponseProcessorInterface;
use BitBag\SyliusImojePlugin\Resolver\Payment\ImojeTransactionPaymentResolverInterface;
use BitBag\SyliusImojePlugin\Resolver\Webhook\oneClickWebhookResolverInterface;
use BitBag\SyliusImojePlugin\Resolver\Webhook\WebhookResolverInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class WebhookController
{
    public const SIGNATURE_HEADER_NAME = 'X-Imoje-Signature';

    private ImojeTransactionPaymentResolverInterface $imojeTransactionPaymentResolver;

    private WebhookResolverInterface $webhookResolver;

    private WebhookResponseProcessorInterface $webhookResponseProcessor;

    private oneClickWebhookResolverInterface $oneClickWebhookResolver;

    public function __construct(
        ImojeTransactionPaymentResolverInterface $imojeTransactionPaymentResolver,
        WebhookResolverInterface $webhookResolver,
        WebhookResponseProcessorInterface $webhookResponseProcessor,
        oneClickWebhookResolverInterface $oneClickWebhookResolver,
    ) {
        $this->imojeTransactionPaymentResolver = $imojeTransactionPaymentResolver;
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
        $payment = $this->imojeTransactionPaymentResolver->resolve($webhookModel->getTransactionId());

        $this->webhookResponseProcessor->process($webhookModel, $payment);

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }
}
