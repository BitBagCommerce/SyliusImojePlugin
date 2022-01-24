<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class SerializerFactory implements SerializerFactoryInterface
{
    public function createSerializerWithNormalizer(): SerializerInterface
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        return new Serializer($normalizers, $encoders);
    }
}
