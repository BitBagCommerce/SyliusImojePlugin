<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Signature;

interface OwnSignatureResolverInterface
{
    public function resolve(): string;
}
