<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin;

use BitBag\SyliusIngPlugin\DependencyInjection\CompilerPass\MessageBusPolyfillPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

final class SyliusIngPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(
            new MessageBusPolyfillPass(),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            1
        );
    }
}
