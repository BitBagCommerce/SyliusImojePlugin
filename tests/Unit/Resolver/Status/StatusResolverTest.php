<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPlugin\Unit\Resolver\Status;

use BitBag\SyliusIngPlugin\Exception\NoCorrectStatusException;
use BitBag\SyliusIngPlugin\Resolver\Status\StatusResolver;
use BitBag\SyliusIngPlugin\Resolver\Status\StatusResolverInterface;
use PHPUnit\Framework\TestCase;

final class StatusResolverTest extends TestCase
{
    protected const REJECTED_STATUS = 'rejected';
    protected const BAD_STATUS = 'bad_status';

    protected StatusResolverInterface $statusResolver;

    protected function setUp(): void
    {
        $this->statusResolver = new StatusResolver();
    }

    public function testResolveStatus(): void
    {
        self::assertEquals($this->statusResolver::ERROR_STATUS, $this->statusResolver->resolve(self::REJECTED_STATUS));
    }

    public function testResolveWithException(): void
    {
        $this->expectException(NoCorrectStatusException::class);
        $this->statusResolver->resolve(self::BAD_STATUS);
    }
}
