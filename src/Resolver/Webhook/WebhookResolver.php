<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Webhook;

use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use BitBag\SyliusIngPlugin\Factory\Status\StatusResponseModelFactoryInterface;
use BitBag\SyliusIngPlugin\Model\Status\StatusResponseModelInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class WebhookResolver implements WebhookResolverInterface
{
    private RequestStack $requestStack;

    private StatusResponseModelFactoryInterface $statusResponseModelFactory;

    public function __construct(RequestStack $requestStack, StatusResponseModelFactoryInterface $statusResponseModelFactory)
    {
        $this->requestStack = $requestStack;
        $this->statusResponseModelFactory = $statusResponseModelFactory;
    }

    public function resolve(): StatusResponseModelInterface
    {
        $request = $this->requestStack->getCurrentRequest();

        $content = json_decode($request->getContent(), true);

        if (null === $content['transaction'] || null === $content['payment']) {
            throw new IngBadRequestException('No found data in request');
        }

        $transactionId = $content['transaction']['id'];
        $paymentId = $content['payment']['id'];
        $orderId = $content['transaction']['orderId'];
        $transactionStatus = $content['transaction']['status'];

        return $this->statusResponseModelFactory->create($transactionId, $paymentId, $orderId, $transactionStatus);
    }
}
