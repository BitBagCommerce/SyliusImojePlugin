<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Customer;

use Sylius\Component\Core\Model\OrderInterface;

final class CustomerResolver implements CustomerResolverInterface
{
    public function resolveFirstname(OrderInterface $order): ?string
    {
        $customer = $order->getCustomer();

        if (null !== $customer->getFirstName()) {
            return $customer->getFirstName();
        }

        return $order->getBillingAddress()->getFirstName();
    }

    public function resolveLastname(OrderInterface $order): ?string
    {
        $customer = $order->getCustomer();

        if (null !== $customer->getLastName()) {
            return $customer->getLastName();
        }

        return $order->getBillingAddress()->getLastName();
    }

    public function resolvePhoneNumber(OrderInterface $order): ?string
    {
        $customer = $order->getCustomer();

        if (null !== $customer->getPhoneNumber()) {
            return $customer->getPhoneNumber();
        }

        return $order->getBillingAddress()->getPhoneNumber();
    }
}
