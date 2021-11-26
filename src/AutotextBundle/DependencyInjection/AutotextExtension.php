<?php

namespace AutotextBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AutotextExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $optionDef = new Definition('AutotextBundle\Autotext');
        $optionDef->setAutowired(true)->setAutoconfigured(true);
        $container->setDefinition('autotext', $optionDef);

        $optionDef = new Definition('AutotextBundle\Twig\AutotextExtension');
        $optionDef->addArgument(new Reference('autotext'));
        $optionDef->setPublic(false);
        $optionDef->addTag('twig.extension');
        $container->setDefinition('AutotextBundle\Twig\AutotextExtension', $optionDef);
    }

    public function getAlias(): string
    {
        return 'autotext';
    }
}
