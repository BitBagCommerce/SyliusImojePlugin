<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Model;

use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;
use BitBag\SyliusIngPayPlugin\Factory\Request\RedirectFactoryInterface;
use BitBag\SyliusIngPayPlugin\Model\Blik\BlikModelInterface;
use BitBag\SyliusIngPayPlugin\Model\TransactionBlikModel;
use BitBag\SyliusIngPayPlugin\Model\TransactionModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class TransactionBlikModelFactory implements TransactionBlikModelFactoryInterface
{
    private CustomerModelFactoryInterface $customerFactory;

    private BillingModelFactoryInterface $billingFactory;

    private ShippingModelFactoryInterface $shippingFactory;

    private RedirectFactoryInterface $redirectModelFactory;

    public function __construct(
        CustomerModelFactoryInterface $customerFactory,
        BillingModelFactoryInterface $billingFactory,
        ShippingModelFactoryInterface $shippingFactory,
        RedirectFactoryInterface $redirectModelFactory,
    ) {
        $this->customerFactory = $customerFactory;
        $this->billingFactory = $billingFactory;
        $this->shippingFactory = $shippingFactory;
        $this->redirectModelFactory = $redirectModelFactory;
    }

    public function create(
        OrderInterface $order,
        IngPayClientConfigurationInterface $ingPayClientConfiguration,
        string $type,
        string $paymentMethod,
        string $paymentMethodCode,
        string $serviceId,
        BlikModelInterface $blikModel,
    ): TransactionModelInterface {
        $redirectModel = $this->redirectModelFactory->create($order->getLastPayment());
        $amount = $order->getTotal();
        $currency = $order->getCurrencyCode();
        $orderId = (string) $order->getId();
        $title = (string) $order->getId();
        $successReturnUrl = $redirectModel->getSuccessUrl();
        $failureReturnUrl = $redirectModel->getFailureUrl();
        $customer = $this->customerFactory->create($order);
        $billing = $this->billingFactory->create($order);
        $shipping = $this->shippingFactory->create($order);
        $blikCode = $blikModel->getBlikCode();
        $clientIp = $blikModel->getClientIp();

        return new TransactionBlikModel(
            $type,
            $serviceId,
            $amount,
            $currency,
            $title,
            $orderId,
            $paymentMethod,
            $paymentMethodCode,
            $successReturnUrl,
            $failureReturnUrl,
            $clientIp,
            $blikCode,
            $customer,
            $billing,
            $shipping,
        );
    }
}
