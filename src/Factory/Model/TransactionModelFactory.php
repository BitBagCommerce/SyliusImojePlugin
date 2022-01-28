<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Model\TransactionModel;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class TransactionModelFactory
{
    public function createTransactionModel(
        OrderInterface $order,
        IngClientConfigurationInterface $ingClientConfiguration,
        string $type,
        string $paymentMethod,
        string $paymentMethodCode
    ): TransactionModelInterface {
        $customerFactory = new CustomerModelFactory();
        $billingFactory = new BillingModelFactory();
        $shippingFactory = new ShippingModelFactory();

        $serviceId = $ingClientConfiguration->getMerchantId();
        $amount = $order->getTotal();
        $currency = $order->getCurrencyCode();
        $orderId = $order->getId();
        $title = 'title';
        $successReturnUrl = 'success';
        $failureReturnUrl = 'success';
        $customer = $customerFactory->createCustomerModel($order);
        $billing = $billingFactory->createBillingModel($order);
        $shipping = $shippingFactory->createShippingModel($order);

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
