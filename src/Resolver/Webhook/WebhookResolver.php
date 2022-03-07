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

        $content = \json_decode($request->getContent(), true);
        $transaction = $content['transaction'];
        $payment = $content['payment'];

        if (null === $transaction || null === $payment) {
            throw new IngBadRequestException('Missing transaction data');
        }

        $transactionId = $transaction['id'] ?? '';
        $paymentId = $payment['id'] ?? '';
        $orderId = $transaction['orderId'] ?? '';
        $transactionStatus = $transaction['status'] ?? '';

        if ('' === $transactionId || '' === $paymentId || '' === $orderId || '' === $transactionStatus) {
            throw new IngBadRequestException('Missing transaction data');
        }

        return $this->statusResponseModelFactory->create($transactionId, $paymentId, $orderId, $transactionStatus);
    }
}
