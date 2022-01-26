<?php

declare(strict_types=1);

namespace Unit\Resolver;

use BitBag\SyliusIngPlugin\Resolver\Configuration\ConfigurationResolver;
use BitBag\SyliusIngPlugin\Resolver\Configuration\ConfigurationResolverInterface;
use PHPUnit\Framework\TestCase;

final class ConfigurationResolverTest extends TestCase
{
    private ConfigurationResolverInterface $configurationResolver;

    protected function setUp(): void
    {
        $this->configurationResolver = new ConfigurationResolver();
    }

    public function test_return_configuration_options(): void
    {
        self::assertIsArray($this->configurationResolver->resolve([]));
    }
}
