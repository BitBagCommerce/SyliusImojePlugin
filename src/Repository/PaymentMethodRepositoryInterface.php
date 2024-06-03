<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Repository;

use Sylius\Component\Core\Model\PaymentMethodInterface;

interface PaymentMethodRepositoryInterface
{
    public const FACTORY_NAME = 'BitBag_imoje';

    public function findOneForImojeCode(string $code): ?PaymentMethodInterface;

    public function findOneForImoje(): ?PaymentMethodInterface;
}
