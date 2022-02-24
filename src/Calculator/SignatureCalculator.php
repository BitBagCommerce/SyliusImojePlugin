<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Calculator;

use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use Psr\Log\LoggerInterface;

final class SignatureCalculator implements SignatureCalculatorInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function calculate(string $incomingSignature, string $ownSignature): void
    {
        if (hash_equals($ownSignature, $incomingSignature)) {
            $this->logger->debug('Authorized request from ing');
        } else {
            $this->logger->error('Unauthorized request');

            throw new IngBadRequestException('Bad request from ing');
        }
    }
}
