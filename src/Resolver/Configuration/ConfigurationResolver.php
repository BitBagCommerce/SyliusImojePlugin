<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

final class ConfigurationResolver implements ConfigurationResolverInterface
{
    public function resolve(array $config): array
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults([
            'token' => '',
            'merchantId' => '',
            'redirect' => '',
            'serviceId' => '',
            'sandboxUrl' => '',
            'prodUrl' => '',
            'isProd' => '',
            'shopKey' => '',
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
        ]);

        $resolver->setRequired($resolver->getDefinedOptions());

        return $resolver->resolve($config);
    }
}
