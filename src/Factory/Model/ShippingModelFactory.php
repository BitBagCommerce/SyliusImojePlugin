<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Model;

use BitBag\SyliusIngPayPlugin\Model\ShippingModel;
use BitBag\SyliusIngPayPlugin\Model\ShippingModelInterface;
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
