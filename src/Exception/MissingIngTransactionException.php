<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Exception;

final class MissingIngTransactionException extends \InvalidArgumentException
{
    public function __construct(string $transactionId)
    {
        parent::__construct(
            \sprintf('No Ing transaction with id %s found.', $transactionId)
        );
    }
}
