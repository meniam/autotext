<?php

namespace AutotextBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class AutotextBundle extends Extension
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
//            $optionDef->addArgument(new Reference('event_dispatcher'));
//            $optionDef->addArgument(new Reference('templating'));
//
//            if (!empty($options['from'])) {
//                $optionDef->addMethodCall('setFrom', [ $options['from'] ]);
//            }
//            if (!empty($options['reply_to'])) {
//                $optionDef->addMethodCall('setReplyTo', [ $options['reply_to'] ]);
//            }
//            if (!empty($options['embed_images'])) {
//                $optionDef->addMethodCall('setEmbedImages', [ $options['embed_images']['url'], $options['embed_images']['path'] ]);
//            }
//            if (!empty($options['domain'])) {
//                $optionDef->addMethodCall('setDomain', [ $options['domain'] ]);
//            }
//            if (!empty($options['template_path'])) {
//                $optionDef->addMethodCall('setTemplatePath', [ $options['template_path'] ]);
//            }
//            if (!empty($options['css_file'])) {
//                $optionDef->addMethodCall('setCssFile', [ $options['css_file'] ]);
//            }
//            $container->setDefinition($optionId, $optionDef);
//            if ($alias === 'default') {
//                $container->setAlias('rage_notification.email.message', $optionId);
//            }
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
