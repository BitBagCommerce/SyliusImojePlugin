<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Exception;

final class MissingPaymentException extends \InvalidArgumentException implements LoggableExceptionInterface
{
    public function __construct(int $transactionId)
    {
        parent::__construct(
            \sprintf('Transaction %s has no payment associated with it.', $transactionId)
        );
    }
}
