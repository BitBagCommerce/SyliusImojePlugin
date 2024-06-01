<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Model;

use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;
use BitBag\SyliusImojePlugin\Factory\Request\RedirectFactoryInterface;
use BitBag\SyliusImojePlugin\Model\TransactionModel;
use BitBag\SyliusImojePlugin\Model\TransactionModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class TransactionModelFactory implements TransactionModelFactoryInterface
{
    private CustomerModelFactoryInterface $customerFactory;

    private BillingModelFactoryInterface $billingFactory;

    private ShippingModelFactoryInterface $shippingFactory;

    private RedirectFactoryInterface $redirectModelFactory;

    public function __construct(
        CustomerModelFactoryInterface $customerFactory,
        BillingModelFactoryInterface $billingFactory,
        ShippingModelFactoryInterface $shippingFactory,
        RedirectFactoryInterface $redirectModelFactory
    ) {
        $this->customerFactory = $customerFactory;
        $this->billingFactory = $billingFactory;
        $this->shippingFactory = $shippingFactory;
        $this->redirectModelFactory = $redirectModelFactory;
    }

    public function create(
        OrderInterface                    $order,
        ImojeClientConfigurationInterface $ingClientConfiguration,
        string                            $type,
        string                            $paymentMethod,
        string                            $paymentMethodCode,
        string                            $serviceId
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

        return new TransactionModel(
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
            $customer,
            $billing,
            $shipping
        );
    }
}
