<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Model\BillingModel;
use BitBag\SyliusIngPlugin\Model\BillingModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class BillingModelFactory
{
    public function createBillingModel(OrderInterface $order): BillingModelInterface
    {
        $customer = $order->getCustomer();
        $billingAddress = $order->getBillingAddress();

        $firstName = $customer->getFirstName();
        $lastName = $customer->getLastName();
        $company = $billingAddress->getCompany();
        $street = $billingAddress->getStreet();
        $city = $billingAddress->getCity();
        $region = '';
        $postalCode = $billingAddress->getPostcode();
        $countryCode = $billingAddress->getCountryCode();

        return new BillingModel($firstName,$lastName,$company,$street,$city,$region,$postalCode,$countryCode);
    }
}
