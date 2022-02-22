<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Exception;

final class InvalidSignatureException extends \InvalidArgumentException implements LoggableExceptionInterface
{
    public function __construct(string $signature)
    {
        parent::__construct(\sprintf(
            'Invalid signature: %s',
            $signature
        ));
    }
}
