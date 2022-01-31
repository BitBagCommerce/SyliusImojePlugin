<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Model\TransactionModel;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
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
        OrderInterface $order,
        IngClientConfigurationInterface $ingClientConfiguration,
        string $type,
        string $paymentMethod,
        string $paymentMethodCode
    ): TransactionModelInterface {
        $redirectModel = $this->redirectModelFactory->create();
        $serviceId = $ingClientConfiguration->getMerchantId();
        $amount = $order->getTotal();
        $currency = $order->getCurrencyCode();
        $orderId = $order->getId();
        $title = $order->getId();
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
