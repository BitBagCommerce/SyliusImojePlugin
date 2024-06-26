<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model;

interface BillingModelInterface
{
    public function getFirstName(): string;

    public function getLastName(): string;

    public function getCompany(): ?string;

    public function getStreet(): ?string;

    public function getCity(): ?string;

    public function getRegion(): ?string;

    public function getPostalCode(): ?string;
}
