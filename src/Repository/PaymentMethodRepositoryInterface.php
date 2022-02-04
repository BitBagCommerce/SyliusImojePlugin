<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Repository;

use Sylius\Component\Core\Model\PaymentMethodInterface;

interface PaymentMethodRepositoryInterface
{
    public const FACTORY_NAME = 'bitbag_ing';

    public function findOneForIngCode(string $code): ?PaymentMethodInterface;

    public function findOneForIng(): ?PaymentMethodInterface;
}
