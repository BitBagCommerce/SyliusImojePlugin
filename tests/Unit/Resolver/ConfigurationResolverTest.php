<?php

declare(strict_types=1);

namespace Unit\Resolver;

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
            'token' => '1234',
            'merchantId' => '1234',
            'redirect' => true,
            'sandboxUrl' => 'sandbox',
            'prodUrl' => 'prod',
            'isProd' => true,
            'blik' => '',
            'card' => '',
            'ing' => '',
            'pbl' => '',
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
