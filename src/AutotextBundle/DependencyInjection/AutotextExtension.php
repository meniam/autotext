<?php

namespace AutotextBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class AutotextExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $config = array();
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }

        foreach ($config as $alias => $options) {
            $optionDef = new Definition($container->getParameter('autotext.class'));
            $optionDef->setShared(false);
            $optionDef->addArgument(new Reference('service.autotext'));
        }
        die;
    }
    /**
     * @return string
     */
    public function getAlias()
    {
        return 'autotext';
    }
}
