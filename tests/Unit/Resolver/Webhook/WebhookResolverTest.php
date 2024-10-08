<?php

declare(strict_types=1);

namespace Unit\Resolver\Webhook;

use BitBag\SyliusImojePlugin\Factory\Status\StatusResponseModelFactoryInterface;
use BitBag\SyliusImojePlugin\Model\Status\StatusResponseModel;
use BitBag\SyliusImojePlugin\Resolver\Webhook\WebhookResolver;
use BitBag\SyliusImojePlugin\Resolver\Webhook\WebhookResolverInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class WebhookResolverTest extends TestCase
{
    private RequestStack $requestStack;

    private StatusResponseModelFactoryInterface $statusResponseModelFactory;

    private WebhookResolverInterface $webhookResolver;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->statusResponseModelFactory = $this->createMock(StatusResponseModelFactoryInterface::class);
        $this->webhookResolver = new WebhookResolver($this->requestStack, $this->statusResponseModelFactory);
    }

    public function testResolveStatus(): void
    {
        $statusModel = new StatusResponseModel('9d5287d4', '191dbb245c5f', '145', 'settled');
        $requestMock = $this->createMock(Request::class);

        $content =
            '{"transaction":{"id":"9d5287d4","status":"settled","orderId":"145"},
            "payment":{"id":"191dbb245c5f"}}';

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($requestMock);

        $requestMock
            ->method('getContent')
            ->willReturn($content);

        $this->statusResponseModelFactory
            ->method('create')
            ->with('9d5287d4', '191dbb245c5f', '145', 'settled')
            ->willReturn($statusModel);

        $result = $this->webhookResolver->resolve();
        self::assertEquals('145', $result->getOrderId());
    }
}
