<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class SerializerFactory implements SerializerFactoryInterface
{
    public function createSerializerWithNormalizer(): Serializer
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        return new Serializer($normalizers, $encoders);
    }
}
