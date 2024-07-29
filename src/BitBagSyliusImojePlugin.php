<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin;

use BitBag\SyliusImojePlugin\DependencyInjection\CompilerPass\MessageBusPolyfillPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BitBagSyliusImojePlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getContainerExtension(): ?ExtensionInterface
    {
        $this->containerExtension = $this->createContainerExtension() ?? false;

        return false !== $this->containerExtension ? $this->containerExtension : null;
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(
            new MessageBusPolyfillPass(),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            1,
        );
    }
}
