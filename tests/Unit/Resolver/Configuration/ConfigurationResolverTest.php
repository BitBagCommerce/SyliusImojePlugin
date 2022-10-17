<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusIngPlugin\Unit\Resolver\Configuration;

use BitBag\SyliusIngPlugin\Resolver\Configuration\ConfigurationResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

final class ConfigurationResolverTest extends TestCase
{
    /** @var ConfigurationResolver */
    private $resolver;

    protected function setUp(): void
    {
        $this->resolver = new ConfigurationResolver();
    }

    public function testValidConfiguration(): void
    {
        $config = [
            'token' => '',
            'merchantId' => '',
            'serviceId' => '',
            'sandboxUrl' => '',
            'prodUrl' => '',
            'isProd' => '',
            'shopKey' => '',
            'blik' => '',
            'card' => '',
            'pbl' => '',
            'ing' => '',
            'mtransfer' => '',
            'bzwbk' => '',
            'pekao24' => '',
            'inteligo' => '',
            'ipko' => '',
            'getin' => '',
            'noble' => '',
            'creditagricole' => '',
            'alior' => '',
            'pbs' => '',
            'millennium' => '',
            'citi' => '',
            'bos' => '',
            'bnpparibas' => '',
            'pocztowy' => '',
            'plusbank' => '',
            'bs' => '',
            'bspb' => '',
            'nest' => '',
            'envelo' => '',
            'imoje_paylater' => '',
            'imoje_twisto' => '',
            'paypo' => '',
        ];

        $resolved = $this->resolver->resolve($config);

        self::assertEquals($config, $resolved);
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(UndefinedOptionsException::class);

        $this->resolver->resolve(['nonexistent' => '']);
    }
}
