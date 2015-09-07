<?php
/**
 * @category   PHP
 * @package    intense-programming
 * @version    1
 * @date       27/07/2015 21:53
 * @author     Konrad, Steve <skonrad@wingmail.net>
 * @copyright  2015, IntenseProgramming
 */

namespace IntenseProgramming\LanguageSwitcherBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 *
 * @package   IntenseProgramming\LanguageSwitcher\DependencyInjection
 * @author    Konrad, Steve <skonrad@wingmail.net>
 * @copyright 2015, IntenseProgramming
 * @date      27/07/2015 21:53
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('intense_programming_language_switch');

        return $treeBuilder;
    }
}
