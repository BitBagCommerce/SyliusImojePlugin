<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Serializer;

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class SerializerFactory implements SerializerFactoryInterface
{
    public function createSerializerWithNormalizer(): Serializer
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer(
                null,
                null,
                null,
                new PropertyInfoExtractor(
                    [],
                    [new PhpDocExtractor(), new ReflectionExtractor()],
                ),
            ),
        ];

        return new Serializer($normalizers, $encoders);
    }
}
