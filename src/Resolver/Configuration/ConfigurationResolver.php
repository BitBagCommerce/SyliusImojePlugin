<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

final class ConfigurationResolver implements ConfigurationResolverInterface
{
    public function resolve(array $config): array
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults([
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
        ]);

        $resolver->setRequired($resolver->getDefinedOptions());

        return $resolver->resolve($config);
    }
}
