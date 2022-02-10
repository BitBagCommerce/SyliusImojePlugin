<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Customer;

use Sylius\Component\Core\Model\OrderInterface;

final class CustomerResolver implements CustomerResolverInterface
{
    public function resolveFirstname(OrderInterface $order): ?string
    {
        $customer = $order->getCustomer();

        if ($customer->getFirstName() !== null) {
            return $customer->getFirstName();
        }

        return $order->getBillingAddress()->getFirstName();
    }

    public function resolveLastname(OrderInterface $order): ?string
    {
        $customer = $order->getCustomer();

        if ($customer->getLastName() !== null) {
            return $customer->getLastName();
        }

        return $order->getBillingAddress()->getLastName();
    }

    public function resolvePhoneNumber(OrderInterface $order): ?string
    {
        $customer = $order->getCustomer();

        if ($customer->getPhoneNumber() !== null) {
            return $customer->getPhoneNumber();
        }

        return $order->getBillingAddress()->getPhoneNumber();
    }
}
