<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop;

use BitBag\SyliusIngPlugin\Bus\Command\TakeOverPayment;
use BitBag\SyliusIngPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;
use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\Payment\OrderPaymentResolverInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class InitializePaymentController
{
    private const CART_SUMMARY_ROUTE = 'sylius_shop_cart_summary';

    private IngClientProviderInterface $ingClientProvider;

    private OrderResolverInterface $orderResolver;

    private OrderPaymentResolverInterface $paymentResolver;

    private UrlGeneratorInterface $urlGenerator;

    private DispatcherInterface $dispatcher;

    public function __construct(
        IngClientProviderInterface $ingClientProvider,
        OrderResolverInterface $orderResolver,
        OrderPaymentResolverInterface $paymentResolver,
        UrlGeneratorInterface $urlGenerator,
        DispatcherInterface $dispatcher
    ) {
        $this->ingClientProvider = $ingClientProvider;
        $this->orderResolver = $orderResolver;
        $this->paymentResolver = $paymentResolver;
        $this->urlGenerator = $urlGenerator;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(Request $request): Response
    {
        $code = $request->query->get('code');
        $order = $this->orderResolver->resolve();

        try {
            $payment = $this->paymentResolver->resolve($order);
        } catch (\InvalidArgumentException $e) {
            return $this->cartSummaryResponse();
        }

        if ($code !== null) {
            $this->dispatcher->dispatch(new TakeOverPayment($payment, $code));
        }

        return new Response();
    }

    private function cartSummaryResponse(): JsonResponse
    {
        return new JsonResponse([
            'redirectUrl' => $this->urlGenerator->generate(self::CART_SUMMARY_ROUTE),
        ]);
    }
}
