<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Model\ShippingModel;
use BitBag\SyliusIngPlugin\Model\ShippingModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class ShippingModelFactory implements ShippingModelFactoryInterface
{
    public function create(OrderInterface $order): ShippingModelInterface
    {
        $customer = $order->getCustomer();
        $shippingAddress = $order->getShippingAddress();

        $firstName = $customer->getFirstName();
        $lastName = $customer->getLastName();
        $company = $shippingAddress->getCompany();
        $street = $shippingAddress->getStreet();
        $city = $shippingAddress->getCity();
        $region = $shippingAddress->getProvinceName();
        $postalCode = $shippingAddress->getPostcode();
        $countryCode = $shippingAddress->getCountryCode();

        return new ShippingModel($firstName, $lastName, $company, $street, $city, $region, $postalCode, $countryCode);
    }
}
