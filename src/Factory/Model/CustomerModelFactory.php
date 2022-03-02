<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Model\CustomerModel;
use BitBag\SyliusIngPlugin\Model\CustomerModelInterface;
use BitBag\SyliusIngPlugin\Resolver\Customer\CustomerResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class CustomerModelFactory implements CustomerModelFactoryInterface
{
    private CustomerResolverInterface $customerResolver;

    public function __construct(CustomerResolverInterface $customerResolver)
    {
        $this->customerResolver = $customerResolver;
    }

    public function create(OrderInterface $order): CustomerModelInterface
    {
        $customer = $order->getCustomer();
        $firstName = $this->customerResolver->resolveFirstname($order);
        $lastName = $this->customerResolver->resolveLastname($order);
        $cid = (string) $customer->getId();
        $company = null === $order->getBillingAddress()->getCompany() ? '' : $order->getBillingAddress()->getCompany();
        $phone = $this->customerResolver->resolvePhoneNumber($order);
        $email = $customer->getEmail();
        $locale = \strtolower($order->getBillingAddress()->getCountryCode());

        return new CustomerModel($firstName, $lastName, $cid, $company, $phone, $email, $locale);
    }
}
