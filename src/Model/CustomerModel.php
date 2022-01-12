<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model;

final class CustomerModel
{
    private string $firstName;

    private string $lastName;

    private string $cid;

    private string $company;

    private string $phone;

    private string $email;

    public function __construct(
        string $firstName,
        string $lastName,
        string $cid,
        string $company,
        string $phone,
        string $email
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->cid = $cid;
        $this->company = $company;
        $this->phone = $phone;
        $this->email = $email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getCid(): string
    {
        return $this->cid;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
