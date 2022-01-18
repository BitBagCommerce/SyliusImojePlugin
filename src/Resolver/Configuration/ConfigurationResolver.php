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
            'redirect' => '',
            'sandboxUrl' => '',
            'prodUrl' => [],
            'isProd' => [],
        ]);

        $resolver->setRequired($resolver->getDefinedOptions());

        return $resolver->resolve($config);
    }
}
