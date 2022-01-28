<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Model\CustomerModel;
use BitBag\SyliusIngPlugin\Model\CustomerModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class CustomerModelFactory
{
    public function createBillingModel(OrderInterface $order): CustomerModelInterface
    {
        $customer = $order->getCustomer();

        $firstName = $customer->getFirstName();
        $lastName = $customer->getLastName();
        $cid = (string)$customer->getId();
        $company = $order->getBillingAddress()->getCompany();
        $phone = $customer->getPhoneNumber();
        $email = $customer->getEmail();
        $locale = \strtolower($order->getLocaleCode());

        return new CustomerModel($firstName,$lastName,$cid, $company, $phone, $email, $locale);
    }
}
