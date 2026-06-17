<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Exception;

final class MissingIngPayTransactionException extends \InvalidArgumentException
{
    public function __construct(string $transactionId)
    {
        parent::__construct(
            \sprintf('No IngPay transaction with id %s found.', $transactionId),
        );
    }
}
