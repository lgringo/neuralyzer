<?php
/**
 * neuralyzer : Data Anonymization Library and CLI Tool
 *
 * PHP Version 7.1
 *
 * @author Emmanuel Dyan
 * @author Rémi Sauvat
 * @copyright 2018 Emmanuel Dyan
 *
 * @package edyan/neuralyzer
 *
 * @license GNU General Public License v2.0
 *
 * @link https://github.com/edyan/neuralyzer
 */

namespace Inet\Neuralyzer\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configuration Validation
 */
class AnonConfiguration implements ConfigurationInterface
{
    /**
     * Validate the configuration
     *
     * The config structure is something like :
     * ## Root
     * entities:
     *    ## Can be repeated : the name of the table, is an array
     *    accounts:
     *        cols:
     *            ## Can be repeated : the name of the field, is an array
     *            name:
     *                method: words # Required: name of the method
     *                params: [8] # Optional: parameters (an array)
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');
        $rootNode
            ->children()
                ->scalarNode('guesser_version')->isRequired()->end()
                ->arrayNode('entities')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->arrayNode('cols')
                                ->requiresAtLeastOneElement()
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('method')->isRequired()->end()
                                        ->arrayNode('params')
                                            ->requiresAtLeastOneElement()->prototype('variable')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->scalarNode('delete')->defaultValue(false)->end()
                            ->scalarNode('delete_where')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
