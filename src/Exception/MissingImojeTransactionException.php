<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Exception;

final class MissingImojeTransactionException extends \InvalidArgumentException
{
    public function __construct(string $transactionId)
    {
        parent::__construct(
            \sprintf('No Imoje transaction with id %s found.', $transactionId)
        );
    }
}
