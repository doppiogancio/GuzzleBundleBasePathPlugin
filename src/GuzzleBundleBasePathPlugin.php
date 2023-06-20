<?php

namespace Doppiogancio\Bundle\GuzzleBundleBasePathPlugin;

use Doppiogancio\Bundle\GuzzleBundleBasePathPlugin\Middleware\BasePathMiddleware;
use EightPoints\Bundle\GuzzleBundle\PluginInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GuzzleBundleBasePathPlugin extends Bundle implements PluginInterface
{
    /**
     * @return string
     */
    public function getPluginName() : string
    {
        return 'base_path';
    }

    /**
     * @param ArrayNodeDefinition $pluginNode
     */
    public function addConfiguration(ArrayNodeDefinition $pluginNode): void
    {
        $pluginNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('base_path')->defaultNull()->end()
            ->end();
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     * @param string $clientName
     * @param Definition $handler
     */
    public function loadForClient(array $config, ContainerBuilder $container, string $clientName, Definition $handler): void
    {
        if ($config['base_path']) {
            // Create DI definition of middleware
            $middleware = new Definition(BasePathMiddleware::class);
            $middleware->setArguments([$config['base_path']]);
            $middleware->setPublic(true);

            // Register Middleware as a Service
            $middlewareServiceName = sprintf('guzzle_bundle_magic_header_plugin.middleware.magic_header.%s', $clientName);
            $container->setDefinition($middlewareServiceName, $middleware);

            // Inject this service to given Handler Stack
            $middlewareExpression = new Expression(sprintf('service("%s")', $middlewareServiceName));
            $handler->addMethodCall('unshift', [$middlewareExpression]);
        }
    }

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container): void
    {

    }
}