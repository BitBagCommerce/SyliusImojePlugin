<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Model\ShippingModel;
use BitBag\SyliusIngPlugin\Model\ShippingModelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Webmozart\Assert\Assert;

final class ShippingModelFactory implements ShippingModelFactoryInterface
{
    public function create(OrderInterface $order): ShippingModelInterface
    {
        $shippingAddress = $order->getShippingAddress();

        Assert::notNull($shippingAddress);

        $firstName = $shippingAddress->getFirstName();
        $lastName = $shippingAddress->getLastName();
        $company = null === $shippingAddress->getCompany() ? '' : $shippingAddress->getCompany();
        $street = $shippingAddress->getStreet();
        $city = $shippingAddress->getCity();
        $region = $shippingAddress->getProvinceName();
        $postalCode = $shippingAddress->getPostcode();
        $countryCode = $shippingAddress->getCountryCode();

        return new ShippingModel($firstName, $lastName, $company, $street, $city, $region, $postalCode, $countryCode);
    }
}
