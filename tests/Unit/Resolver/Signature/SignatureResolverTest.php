<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPlugin\Unit\Resolver\Signature;

use BitBag\SyliusIngPlugin\Exception\InvalidSignatureException;
use BitBag\SyliusIngPlugin\Resolver\Signature\SignatureResolver;
use BitBag\SyliusIngPlugin\Resolver\Signature\SignatureResolverInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class SignatureResolverTest extends TestCase
{
    private const SIGNATURE = '59186f338f4c3b24a2fa66b5dd029f58f42e397d30c5a13059a2e4468ff5ec4a';

    private SignatureResolverInterface $signatureResolver;

    private RequestStack $requestStack;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->signatureResolver = new SignatureResolver($this->requestStack);
    }

    public function testResolveSignature(): void
    {
        $header = $this->createMock(HeaderBag::class);
        $request = new Request();
        $request->headers = $header;

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($request);

        $header
            ->method('get')
            ->with($this->signatureResolver::SIGNATURE_HEADER)
            ->willReturn(sprintf('signature=%s;alg=sha256', self::SIGNATURE));

        self::assertEquals(self::SIGNATURE, $this->signatureResolver->resolve());
    }

    /**
     * @dataProvider providerDataSignature
     */
    public function testBadSignature(): void
    {
        $this->expectException(InvalidSignatureException::class);
        $header = $this->createMock(HeaderBag::class);
        $request = new Request();
        $request->headers = $header;

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($request);

        $header
            ->method('get')
            ->with($this->signatureResolver::SIGNATURE_HEADER)
            ->willReturn('signature=123$=1#$;alg=bad123');

        $this->signatureResolver->resolve();
    }

    public function testBadAlgorithm(): void
    {
        $this->expectException(InvalidSignatureException::class);

        $header = $this->createMock(HeaderBag::class);
        $request = new Request();
        $request->headers = $header;

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($request);

        $header
            ->method('get')
            ->with($this->signatureResolver::SIGNATURE_HEADER)
            ->willReturn(sprintf('signature=%s;alg=bad123', self::SIGNATURE));

        $this->signatureResolver->resolve();
    }

    public function providerDataSignature()
    {
        return [
          ['signature=123$=1#$;alg=sha256'],
          ['signature=6f338f4c3b24a2fa66b5dd029f58f42e397d=1#$;alg=sha256'],
          ['signature==6f338f4c3b24a2fa66b5dd029f58f42e397d;alg=sha256'],
          ['signature=123$=1#$,.;alg=sha256'],
        ];
    }
}
