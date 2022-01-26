<?php

declare(strict_types=1);

namespace Unit\Factory;

use BitBag\SyliusIngPlugin\Factory\Serializer\SerializerFactory;
use BitBag\SyliusIngPlugin\Factory\Serializer\SerializerFactoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;

final class SerializerFactoryTest extends TestCase
{
    private SerializerFactoryInterface $serializerFactory;

    protected function setUp(): void
    {
        $this->serializerFactory = new SerializerFactory();
    }

    public function test_return_serializer(): void
    {
        $this->assertInstanceOf(
            SerializerInterface::class,
            $this->serializerFactory->createSerializerWithNormalizer()
        );
    }
}
