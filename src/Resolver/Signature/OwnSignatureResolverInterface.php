<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Signature;

interface OwnSignatureResolverInterface
{
    public function resolve(): string;
}
