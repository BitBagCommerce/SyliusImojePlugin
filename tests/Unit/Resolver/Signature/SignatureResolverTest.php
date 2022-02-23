<?php

declare(strict_types=1);

namespace Unit\Resolver\Signature;

use BitBag\SyliusIngPlugin\Exception\InvalidSignatureException;
use BitBag\SyliusIngPlugin\Resolver\Signature\SignatureResolver;
use BitBag\SyliusIngPlugin\Resolver\Signature\SignatureResolverInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class SignatureResolverTest extends TestCase
{
    protected const SIGNATURE = '59186f338f4c3b24a2fa66b5dd029f58f42e397d30c5a13059a2e4468ff5ec4a';
    protected SignatureResolverInterface $statusResolver;
    protected RequestStack $requestStack;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->statusResolver = new SignatureResolver($this->requestStack);
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
            ->with('X-Imoje-Signature')
            ->willReturn(sprintf('signature=%s;alg=sha256', self::SIGNATURE));

        self::assertEquals(self::SIGNATURE, $this->statusResolver->resolve());
    }

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
            ->with('X-Imoje-Signature')
            ->willReturn('Bad signature');

        $this->statusResolver->resolve();
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
            ->with('X-Imoje-Signature')
            ->willReturn(sprintf('signature=%s;alg=bad123', self::SIGNATURE));

        $this->statusResolver->resolve();
    }
}
