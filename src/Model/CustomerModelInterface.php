<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model;

interface CustomerModelInterface
{
    public function getFirstName(): string;

    public function getLastName(): string;

    public function getCid(): ?string;

    public function getCompany(): ?string;

    public function getPhone(): ?string;

    public function getEmail(): string;

    public function getLocale(): ?string;
}
