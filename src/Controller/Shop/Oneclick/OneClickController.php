<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop\Oneclick;

use BitBag\SyliusIngPlugin\Factory\Request\RedirectFactoryInterface;
use BitBag\SyliusIngPlugin\Resolver\GatewayCode\GatewayCodeFromOrderResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\IngOneClickSignature\IngOneClickSignatureResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class OneClickController
{
    public const ING_KEY_DETAIL = 'ingPaymentMethods';

    private GatewayCodeFromOrderResolverInterface $gatewayCodeFromOrderResolver;

    private OrderRepositoryInterface $orderRepository;

    private IngOneClickSignatureResolverInterface $signatureResolver;

    private RedirectFactoryInterface $redirectFactory;

    private OrderResolverInterface $orderResolver;

    public function __construct(
        GatewayCodeFromOrderResolverInterface $gatewayCodeFromOrderResolver,
        OrderRepositoryInterface $orderRepository,
        IngOneClickSignatureResolverInterface $signatureResolver,
        RedirectFactoryInterface $redirectFactory,
        OrderResolverInterface $orderResolver
    ) {
        $this->gatewayCodeFromOrderResolver = $gatewayCodeFromOrderResolver;
        $this->orderRepository = $orderRepository;
        $this->signatureResolver = $signatureResolver;
        $this->redirectFactory = $redirectFactory;
        $this->orderResolver = $orderResolver;
    }

    public function __invoke(Request $request, string $orderId): Response
    {
        $order = $this->orderResolver->resolve($orderId);
        $payment = $order->getLastPayment();
        $customer = $order->getCustomer();
        $address = $order->getBillingAddress();

        Assert::notNull($payment);
        Assert::notNull($customer);
        Assert::notNull($address);

        $config = $this->gatewayCodeFromOrderResolver->resolve($order);
        $url = $this->redirectFactory->createForOneClick($payment);
        $currencyCode = \strtoupper($order->getCurrencyCode() ?? '');

        $data = [
            'merchantId' => $config->getMerchantId(),
            'serviceId' => $config->getServiceId(),
            'amount' => $order->getTotal(),
            'currency' => $currencyCode,
            'orderId' => $order->getId(),
            'customerId' => $customer->getId(),
            'customerFirstName' => $address->getFirstName(),
            'customerLastName' => $address->getLastName(),
            'customerEmail' => $customer->getEmail(),
            'urlFailure' => $url->getFailureUrl(),
            'urlSuccess' => $url->getSuccessUrl(),
        ];

        $session = $request->getSession();
        $session->set('sylius_order_id', $order->getId());
        $signature = $this->signatureResolver->resolve($data, $config);

        return new JsonResponse([
            'merchantId' => $config->getMerchantId(),
            'serviceId' => $config->getServiceId(),
            'amount' => $order->getTotal(),
            'currency' => $currencyCode,
            'orderId' => $order->getId(),
            'customerId' => $address->getId(),
            'customerFirstName' => $address->getFirstName(),
            'customerLastName' => $address->getLastName(),
            'customerEmail' => $customer->getEmail(),
            'urlFailure' => $url->getFailureUrl(),
            'urlSuccess' => $url->getSuccessUrl(),
            'signature' => $signature,
        ]);
    }
}
