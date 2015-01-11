<?php

namespace ScoreYa\Cinderella\Bundle\SDKBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author Alexander Miehe <thelex@beamscore.com>
 */
class ScoreYaCinderellaSDKExtension extends ConfigurableExtension
{
    /**
     * Configures the passed container according to the merged configuration.
     *
     * @param array            $mergedConfig
     * @param ContainerBuilder $container
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $servicesXml = 'services.xml';

        $hasFactoryMethod = method_exists('Symfony\Component\DependencyInjection\Definition', 'setFactory');
        if ($hasFactoryMethod === true) {
            $servicesXml = 'services_26.xml';
        }

        $loader->load($servicesXml);

        $container
            ->getDefinition('score_ya.cinderella.sdk.client_service_builder')
            ->replaceArgument(0, $mergedConfig['api_key']);

        $clientDefinition = 'score_ya.cinderella.sdk.%s_client';

        foreach ($mergedConfig['clients'] as $name => $config) {
            $definition = new Definition($config['class']);

            $factory = array(new Reference('score_ya.cinderella.sdk.client_service_builder'), 'get');

            if ($hasFactoryMethod === true) {
                $definition->setFactory($factory);
            }

            if ($hasFactoryMethod === false) {
                $definition
                    ->setFactoryService($factory[0])
                    ->setFactoryMethod($factory[1]);
            }

            $definition->addArgument($name);

            if (isset($config['base_url']) === true) {
                $definition->addMethodCall('setBaseUrl', array($config['base_url']));
            }

            $container->setDefinition(sprintf($clientDefinition, $name), $definition);
        }
    }
}
