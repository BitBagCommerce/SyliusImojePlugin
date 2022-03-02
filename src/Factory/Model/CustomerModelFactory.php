<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Model\CustomerModel;
use BitBag\SyliusIngPlugin\Model\CustomerModelInterface;
use BitBag\SyliusIngPlugin\Resolver\Customer\CustomerResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Webmozart\Assert\Assert;

final class CustomerModelFactory implements CustomerModelFactoryInterface
{
    private CustomerResolverInterface $customerResolver;

    public function __construct(CustomerResolverInterface $customerResolver)
    {
        $this->customerResolver = $customerResolver;
    }

    public function create(OrderInterface $order): CustomerModelInterface
    {
        $billingAddress = $order->getBillingAddress();
        $customer = $order->getCustomer();

        Assert::notNull($billingAddress);
        Assert::notNull($customer);

        $firstName = $this->customerResolver->resolveFirstname($order);
        $lastName = $this->customerResolver->resolveLastname($order);
        $email = $customer->getEmail();
        $cid = (string) $customer->getId();
        $company = $billingAddress->getCompany() ?? '';
        $phone = $this->customerResolver->resolvePhoneNumber($order);
        $locale = \strtolower($billingAddress->getCountryCode() ?? '');

        return new CustomerModel($firstName, $lastName, $cid, $company, $phone, $email, $locale);
    }
}
