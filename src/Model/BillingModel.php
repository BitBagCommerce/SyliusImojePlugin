<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model;

final class BillingModel implements BillingModelInterface
{
    private string $firstName;

    private string $lastName;

    private ?string $company;

    private ?string $street;

    private ?string $city;

    private ?string $region;

    private ?string $postalCode;

    public function __construct(
        string $firstName,
        string $lastName,
        ?string $company,
        ?string $street,
        ?string $city,
        ?string $region,
        ?string $postalCode
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->company = $company;
        $this->street = $street;
        $this->city = $city;
        $this->region = $region;
        $this->postalCode = $postalCode;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }
}
