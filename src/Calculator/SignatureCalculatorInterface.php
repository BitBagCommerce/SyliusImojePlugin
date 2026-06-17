<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Calculator;

interface SignatureCalculatorInterface
{
    public function calculate(string $incomingSignature, string $ownSignature): void;
}
