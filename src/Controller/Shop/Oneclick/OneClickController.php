<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop\Oneclick;

use BitBag\SyliusIngPlugin\Factory\Request\RedirectFactoryInterface;
use BitBag\SyliusIngPlugin\Resolver\GatewayCode\GatewayCodeFromOrderResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\IngOneClickSignature\IngOneClickSignatureResolverInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class OneClickController
{
    public const ING_KEY_DETAIL = 'ingPaymentMethods';

    private GatewayCodeFromOrderResolverInterface $gatewayCodeFromOrderResolver;

    private IngOneClickSignatureResolverInterface $signatureResolver;

    private RedirectFactoryInterface $redirectFactory;

    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        GatewayCodeFromOrderResolverInterface $gatewayCodeFromOrderResolver,
        IngOneClickSignatureResolverInterface $signatureResolver,
        RedirectFactoryInterface $redirectFactory,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->gatewayCodeFromOrderResolver = $gatewayCodeFromOrderResolver;
        $this->signatureResolver = $signatureResolver;
        $this->redirectFactory = $redirectFactory;
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(Request $request, int $orderId): Response
    {
        $order = $this->orderRepository->find($orderId);
        $address = $order->getBillingAddress();
        $config = $this->gatewayCodeFromOrderResolver->resolve($order);
        $customer = $order->getCustomer();
        $payment = $order->getLastPayment();

        Assert::notNull($payment);
        Assert::notNull($address);

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
            'customerId' => $customer->getId(),
            'customerFirstName' => $address->getFirstName(),
            'customerLastName' => $address->getLastName(),
            'customerEmail' => $customer->getEmail(),
            'urlFailure' => $url->getFailureUrl(),
            'urlSuccess' => $url->getSuccessUrl(),
            'signature' => $signature,
        ]);
    }
}
