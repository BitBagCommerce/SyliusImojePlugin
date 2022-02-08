<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Model\CustomerModel;
use BitBag\SyliusIngPlugin\Model\CustomerModelInterface;
use BitBag\SyliusIngPlugin\Resolver\Customer\CustomerResolver;
use Sylius\Component\Core\Model\OrderInterface;

final class CustomerModelFactory implements CustomerModelFactoryInterface
{
    public function create(OrderInterface $order): CustomerModelInterface
    {
        $dataResolver = new CustomerResolver();

        $customer = $order->getCustomer();
        $firstName = $dataResolver->resolveFirstname($order);
        $lastName = $dataResolver->resolveLastname($order);
        $cid = (string) $customer->getId();
        $company = $order->getBillingAddress()->getCompany();
        $phone = $dataResolver->resolvePhoneNumber($order);
        $email = $customer->getEmail();
        $locale = \strtolower($order->getBillingAddress()->getCountryCode());
        return new CustomerModel($firstName, $lastName, $cid, $company, $phone, $email, $locale);
    }
}
