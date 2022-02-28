<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop\Oneclick;

use BitBag\SyliusIngPlugin\Factory\Request\RedirectFactoryInterface;
use BitBag\SyliusIngPlugin\Resolver\GatewayCode\GatewayCodeFromOrderResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\IngOneClickSignature\IngOneClickSignatureResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class OneClickController
{
    public const ING_KEY_DETAIL = 'ingPaymentMethods';

    private GatewayCodeFromOrderResolverInterface $gatewayCodeFromOrderResolver;

    private OrderRepositoryInterface $orderRepository;

    private IngOneClickSignatureResolverInterface $signatureResolver;

    private RedirectFactoryInterface $redirectFactory;

    public function __construct(
        GatewayCodeFromOrderResolverInterface $gatewayCodeFromOrderResolver,
        OrderRepositoryInterface $orderRepository,
        IngOneClickSignatureResolverInterface $signatureResolver,
        RedirectFactoryInterface $redirectFactory
    ) {
        $this->gatewayCodeFromOrderResolver = $gatewayCodeFromOrderResolver;
        $this->orderRepository = $orderRepository;
        $this->signatureResolver = $signatureResolver;
        $this->redirectFactory = $redirectFactory;
    }

    public function __invoke(Request $request, int $orderId): Response
    {
        /** @var OrderInterface $order */
        $order = $this->orderRepository->find($orderId);
        $customer = $order->getBillingAddress();
        $config = $this->gatewayCodeFromOrderResolver->resolve($order);
        $url = $this->redirectFactory->createForOneClick($order->getLastPayment());

        $data = [
            'merchantId' => $config->getMerchantId(),
            'serviceId' => $config->getServiceId(),
            'amount' => $order->getTotal(),
            'currency' => strtoupper($order->getCurrencyCode()),
            'orderId' => $order->getId(),
            'customerId' => $customer->getId(),
            'customerFirstName' => $customer->getFirstName(),
            'customerLastName' => $customer->getLastName(),
            'customerEmail' => $order->getCustomer()->getEmail(),
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
            'currency' => strtoupper($order->getCurrencyCode()),
            'orderId' => $order->getId(),
            'customerId' => $customer->getId(),
            'customerFirstName' => $customer->getFirstName(),
            'customerLastName' => $customer->getLastName(),
            'customerEmail' => $order->getCustomer()->getEmail(),
            'urlFailure' => $url->getFailureUrl(),
            'urlSuccess' => $url->getSuccessUrl(),
            'signature' => $signature,
        ]);
    }
}
