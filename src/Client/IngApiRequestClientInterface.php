<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Client;

use Psr\Http\Message\ResponseInterface;

interface IngApiRequestClientInterface
{
    public const TRANSACTION_ENDPOINT = 'transaction';

    public function gettingTransactionData(string $url, string $token): ResponseInterface;
}
