<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Model;

use BitBag\SyliusImojePlugin\Model\BillingModel;
use BitBag\SyliusImojePlugin\Model\BillingModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class BillingModelFactory implements BillingModelFactoryInterface
{
    public function create(OrderInterface $order): BillingModelInterface
    {
        $billingAddress = $order->getBillingAddress();

        $firstName = $billingAddress->getFirstName();
        $lastName = $billingAddress->getLastName();
        $company = null === $billingAddress->getCompany() ? '' : $billingAddress->getCompany();
        $street = $billingAddress->getStreet();
        $city = $billingAddress->getCity();
        $region = $billingAddress->getProvinceName() ?? '';
        $postalCode = $billingAddress->getPostcode();

        return new BillingModel($firstName, $lastName, $company, $street, $city, $region, $postalCode);
    }
}
