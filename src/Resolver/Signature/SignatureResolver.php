<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Signature;

use BitBag\SyliusImojePlugin\Exception\InvalidSignatureException;
use Symfony\Component\HttpFoundation\RequestStack;

final class SignatureResolver implements SignatureResolverInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function resolve(): string
    {
        $headerSignature = $this->requestStack->getCurrentRequest()->headers->get(self::SIGNATURE_HEADER) ?? '';

        if (!\preg_match(self::SIGNATURE_REGEX, $headerSignature, $matches)) {
            throw new InvalidSignatureException(
                \sprintf('Invalid signature: [%s]', $headerSignature)
            );
        }

        $signature = $matches[1];
        $alg = $matches[2];

        if (self::SIGNATURE_ALG !== $alg) {
            throw new InvalidSignatureException(
                \sprintf('Invalid hash algorithm: [%s]', $headerSignature)
            );
        }

        return $signature;
    }
}
