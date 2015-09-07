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

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * Class IntenseProgrammingLanguageSwitcherExtension.
 *
 * @package   IntenseProgramming\LanguageSwitcher\DependencyInjection
 * @author    Konrad, Steve <skonrad@wingmail.net>
 * @copyright 2015, IntenseProgramming
 * @date      27/07/2015 21:53
 */
class IntenseProgrammingLanguageSwitcherExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('parameters.yml');
    }

}
