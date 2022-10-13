<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\TransactionMethod;

use Sylius\Component\Core\Model\PaymentInterface;

interface TransactionMethodResolverInterface
{
    public const PAYMENT_METHOD_BLIK = 'blik';

    public const PAYMENT_METHOD_CARD = 'card';

    public const PAYMENT_METHOD_ING = 'ing';

    public const PAYMENT_METHOD_PAY_LATER = 'imoje_paylater';

    public const PAYMENT_METHOD_PBL = 'pbl';

    public function resolve(PaymentInterface $payment): string;
}
