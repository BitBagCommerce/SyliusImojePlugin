<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Signature;

use BitBag\SyliusIngPlugin\Exception\InvalidSignatureException;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class SignatureResolver implements SignatureResolverInterface
{
    private RequestStack $requestStack;

    private IngClientConfigurationProviderInterface $ingClientConfiguration;

    public function __construct(RequestStack $requestStack, IngClientConfigurationProviderInterface $ingClientConfiguration)
    {
        $this->requestStack = $requestStack;
        $this->ingClientConfiguration = $ingClientConfiguration;
    }

    public function resolve(): string
    {
        $headerSignature = $this->requestStack->getCurrentRequest()->headers->get('X-Imoje-Signature');
        if (!\preg_match(self::SIGNATURE_REGEX, $headerSignature, $matches)) {
            throw new InvalidSignatureException(
                \sprintf('Invalid signature: [%s]', $headerSignature)
            );
        }

        $signature = $matches[1];
        $alg = $matches[2];

        if ($alg !== self::SIGNATURE_ALG) {
            throw new InvalidSignatureException(
                \sprintf('Invalid signature: [%s]', $headerSignature)
            );
        }

        return $signature;
    }
}
