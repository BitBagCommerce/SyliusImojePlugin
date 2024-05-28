<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusImojePlugin\Unit\Resolver\Status;

use BitBag\SyliusImojePlugin\Exception\NoCorrectStatusException;
use BitBag\SyliusImojePlugin\Resolver\Status\StatusResolver;
use BitBag\SyliusImojePlugin\Resolver\Status\StatusResolverInterface;
use PHPUnit\Framework\TestCase;

final class StatusResolverTest extends TestCase
{
    private const REJECTED_STATUS = 'rejected';

    private const BAD_STATUS = 'bad_status';

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
