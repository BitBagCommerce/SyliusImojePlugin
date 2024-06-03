<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Calculator;

use BitBag\SyliusImojePlugin\Exception\ImojeBadRequestException;
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
            $this->logger->debug('Authorized request from imoje');
        } else {
            $this->logger->error('Unauthorized request');

            throw new ImojeBadRequestException('Bad request from imoje');
        }
    }
}
