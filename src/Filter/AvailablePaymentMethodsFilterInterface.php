<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Filter;

interface AvailablePaymentMethodsFilterInterface
{
    public const TYPE_CARD = 'card';

    public const TYPE_PBL = 'pbl';

    public const TYPE_PAY_LATER = 'imoje_paylater';

    public function filter(
        string $code,
        string $serviceId,
        array $paymentMethods,
        string $currency,
    ): array;
}
