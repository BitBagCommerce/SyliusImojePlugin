<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Model;

use BitBag\SyliusImojePlugin\Model\ShippingModel;
use BitBag\SyliusImojePlugin\Model\ShippingModelInterface;
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
        $region = $shippingAddress->getProvinceName() ?? '';
        $postalCode = $shippingAddress->getPostcode();

        return new ShippingModel($firstName, $lastName, $company, $street, $city, $region, $postalCode);
    }
}
