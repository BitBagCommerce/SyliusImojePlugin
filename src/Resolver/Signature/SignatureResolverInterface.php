<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Signature;

interface SignatureResolverInterface
{
    public const SIGNATURE_REGEX = '/signature=([a-z0-9]+);alg=([a-z0-9]+)/';

    public const SIGNATURE_ALG = 'sha256';

    public const SIGNATURE_HEADER = 'X-Imoje-Signature';

    public function resolve(): string;
}
