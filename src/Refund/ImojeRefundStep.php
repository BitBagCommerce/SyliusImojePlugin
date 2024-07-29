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
    private ImojeClientProviderInterface $imojeClientProvider;

    private OrderRepositoryInterface $orderRepository;

    private GatewayFactoryNameResolverInterface $gatewayFactoryNameResolver;

    private ImojeClientConfigurationProviderInterface $imojeClientConfigurationProvider;

    private RefundUrlResolverInterface $refundUrlResolver;

    public function __construct(
        ImojeClientProviderInterface $imojeClientProvider,
        OrderRepositoryInterface $orderRepository,
        GatewayFactoryNameResolverInterface $gatewayFactoryNameResolver,
        ImojeClientConfigurationProviderInterface $imojeClientConfigurationProvider,
        RefundUrlResolverInterface $refundUrlResolver,
    ) {
        $this->imojeClientProvider = $imojeClientProvider;
        $this->orderRepository = $orderRepository;
        $this->gatewayFactoryNameResolver = $gatewayFactoryNameResolver;
        $this->imojeClientConfigurationProvider = $imojeClientConfigurationProvider;
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
        $config = $this->imojeClientConfigurationProvider->getPaymentMethodConfiguration($gatewayCode);
        $url = $this->refundUrlResolver->resolve($config, $payment->getId());
        $client = $this->imojeClientProvider->getClient($gatewayCode);

        $client->refundTransaction($url, $config->getServiceId(), $unitsRefunded->amount());
    }
}
