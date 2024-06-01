<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Refund;

use BitBag\SyliusImojePlugin\Provider\ImojeClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientProviderInterface;
use BitBag\SyliusImojePlugin\Resolver\GatewayFactoryName\GatewayFactoryNameResolverInterface;
use BitBag\SyliusImojePlugin\Resolver\Refund\RefundUrlResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\RefundPlugin\Event\UnitsRefunded;
use Sylius\RefundPlugin\ProcessManager\UnitsRefundedProcessStepInterface;

final class ImojeRefundStep implements UnitsRefundedProcessStepInterface
{
    private ImojeClientProviderInterface $ingClientProvider;

    private OrderRepositoryInterface $orderRepository;

    private GatewayFactoryNameResolverInterface $gatewayFactoryNameResolver;

    private ImojeClientConfigurationProviderInterface $ingClientConfigurationProvider;

    private RefundUrlResolverInterface $refundUrlResolver;

    public function __construct(
        ImojeClientProviderInterface              $ingClientProvider,
        OrderRepositoryInterface                  $orderRepository,
        GatewayFactoryNameResolverInterface       $gatewayFactoryNameResolver,
        ImojeClientConfigurationProviderInterface $ingClientConfigurationProvider,
        RefundUrlResolverInterface                $refundUrlResolver
    ) {
        $this->ingClientProvider = $ingClientProvider;
        $this->orderRepository = $orderRepository;
        $this->gatewayFactoryNameResolver = $gatewayFactoryNameResolver;
        $this->ingClientConfigurationProvider = $ingClientConfigurationProvider;
        $this->refundUrlResolver = $refundUrlResolver;
    }

    public function next(UnitsRefunded $unitsRefunded): void
    {
        /** @var OrderInterface $order */
        $order = $this->orderRepository->findOneBy(['number' => $unitsRefunded->orderNumber()]);
        /** @var PaymentInterface $payment */
        $payment = $order->getLastPayment();
        $gatewayCode = $payment->getMethod()->getCode();
        $gatewayFactory = $this->gatewayFactoryNameResolver->resolve($gatewayCode);

        if (ImojeClientConfigurationProviderInterface::FACTORY_NAME !== $gatewayFactory) {
            return;
        }
        $config = $this->ingClientConfigurationProvider->getPaymentMethodConfiguration($gatewayCode);
        $url = $this->refundUrlResolver->resolve($config, $payment->getId());
        $client = $this->ingClientProvider->getClient($gatewayCode);

        $client->refundTransaction($url, $config->getServiceId(), $unitsRefunded->amount());
    }
}
