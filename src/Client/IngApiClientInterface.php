<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Client;

use BitBag\SyliusIngPlugin\Model\CreateTransactionModelInterface;
use Psr\Http\Message\ResponseInterface;

interface IngApiClientInterface
{
    public function sendRequest(
        ?CreateTransactionModelInterface $createTransactionModel,
        string $method,
        string $action
    ): ?ResponseInterface;
}
