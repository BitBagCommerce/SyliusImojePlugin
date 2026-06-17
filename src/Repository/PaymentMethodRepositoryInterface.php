<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Repository;

use Sylius\Component\Core\Model\PaymentMethodInterface;

interface PaymentMethodRepositoryInterface
{
    public const FACTORY_NAME = 'BitBag_ing_pay';

    public function findOneForIngPayCode(string $code): ?PaymentMethodInterface;

    public function findOneForIngPay(): ?PaymentMethodInterface;
}
