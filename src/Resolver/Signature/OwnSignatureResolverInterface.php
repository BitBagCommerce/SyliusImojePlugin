<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Signature;

interface OwnSignatureResolverInterface
{
    public function resolve(): string;
}
