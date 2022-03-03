<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Filter;

interface AvailablePaymentMethodsFilterInterface
{
    public const TYPE_CARD = 'card';

    public const TYPE_PBL = 'pbl';

    public function filter(
        string $code,
        string $serviceId,
        array $paymentMethods
    ): array;
}
