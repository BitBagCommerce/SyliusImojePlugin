<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Model\TransactionModel;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class TransactionModelFactory implements TransactionModelFactoryInterface
{
    private CustomerModelFactory $customerFactory;

    private BillingModelFactory $billingFactory;

    private ShippingModelFactory $shippingFactory;

    private RedirectFactoryInterface $redirectModelFactory;

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->customerFactory = new CustomerModelFactory();
        $this->billingFactory = new BillingModelFactory();
        $this->shippingFactory = new ShippingModelFactory();
        $this->redirectModelFactory = new RedirectFactory($generator);
    }

    public function create(
        OrderInterface $order,
        IngClientConfigurationInterface $ingClientConfiguration,
        string $type,
        string $paymentMethod,
        string $paymentMethodCode
    ): TransactionModelInterface {
        $redirectModel = $this->redirectModelFactory->createNew();
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
