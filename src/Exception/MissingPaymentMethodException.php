<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Exception;

use Sylius\Component\Core\Model\PaymentInterface;

final class MissingPaymentMethodException extends \InvalidArgumentException
{
    public function __construct(PaymentInterface $payment)
    {
        parent::__construct(
            \sprintf('Payment %s does not contain a valid payment method.', $payment->getId())
        );
    }
}
