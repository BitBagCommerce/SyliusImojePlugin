<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop;

use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;
use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class InitializePaymentController
{
    private IngClientProviderInterface $ingClientProvider;

    private OrderResolverInterface $orderResolver;

    public function __construct(IngClientProviderInterface $ingClientProvider, OrderResolverInterface $orderResolver)
    {
        $this->ingClientProvider = $ingClientProvider;
        $this->orderResolver = $orderResolver;
    }

    public function __invoke(Request $request): Response
    {
        $order = $this->orderResolver->resolve();
        $client = $this->ingClientProvider->getClient('ing_code');
        return new Response();
    }
}
