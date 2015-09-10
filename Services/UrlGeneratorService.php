<?php
/**
 * @category   PHP
 * @package    intense-programming
 * @version    1
 * @date       26/07/2015 15:27
 * @author     Konrad, Steve <skonrad@wingmail.net>
 * @copyright  2015, IntenseProgramming
 */

namespace IntenseProgramming\LanguageSwitcherBundle\Services;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ChainConfigResolver;
use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\MVC\Symfony\Routing\ChainRouter;
use eZ\Publish\Core\MVC\Symfony\Routing\Generator\RouteReferenceGeneratorInterface;
use eZ\Publish\Core\MVC\Symfony\SiteAccess;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UrlGeneratorService.
 *
 * @package   IntenseProgramming\DesignBundle\Services
 * @author    Konrad, Steve <skonrad@wingmail.net>
 * @copyright 2015, IntenseProgramming
 * @date      26/07/2015 15:27
 */
class UrlGeneratorService
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var ChainRouter
     */
    protected $router;

    /**
     * @var RouteReferenceGeneratorInterface
     */
    protected $routeGenerator;

    /**
     * @var ContentService
     */
    protected $contentService;

    /**
     * @var ChainConfigResolver
     */
    protected $configResolver;

    /**
     * @var boolean
     */
    private $initialized = false;

    /**
     * @var boolean
     */
    private $fullUrl = false;

    /**
     * @param Container $container Symfony's service-container.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Generates an array containing the different urls to the other languages.
     *
     * @param string|Location $translationParameter
     * @param Request         $request
     *
     * @return array
     */
    public function generateUrls($translationParameter, Request $request)
    {
        $this->init();

        $returnValue = array(
            'current' => null,
            'alternative' => array()
        );

        try {
            $requestAccess = $request->get('siteaccess', false);
            if ($requestAccess instanceof SiteAccess) {
                $translationSiteaccesses = $this->configResolver->getParameter('translation_siteaccesses');

                foreach ($translationSiteaccesses as $siteaccess) {
                    $languages = $this->container->getParameter('ezsettings.' . $siteaccess . '.languages');

                    if (!empty($languages)) {
                        try {
                            $language = $languages[0];

                            if (is_string($translationParameter)) {
                                $description = $this->generateUriRoute(
                                    $translationParameter, $language, $siteaccess
                                );
                            } else {
                                $description = $this->generateLocationRoute(
                                    $translationParameter, $language, $siteaccess
                                );
                            }

                            if ($requestAccess->name == $siteaccess) {
                                $returnValue['current'] = $description;
                            } else {
                                $returnValue['alternative'][] = $description;
                            }
                        } catch (NotFoundException $exception) {
                        }
                    }
                }
            }

            return $returnValue;
        } catch (InvalidArgumentException $exception) {
            return $returnValue;
        }
    }

    /**
     * Generates a result-section for a value of the Location-class.
     *
     * @param Location $location
     * @param string   $language
     * @param string   $siteaccess
     *
     * @return array
     */
    protected function generateLocationRoute(Location $location, $language, $siteaccess)
    {
        $content = $this->contentService->loadContent($location->contentId, array($language));

        $reference = $this->routeGenerator->generate($location, array('language' => $language));
        $url = $this->router->generate($reference, array('siteaccess' => $siteaccess), $this->fullUrl);

        return array(
            'content' => $content,
            'siteaccess' => $siteaccess,
            'languageCode' => $language,
            'url' => $url
        );
    }

    /**
     * Generates a result-section for a string.
     *
     * @param string $uri
     * @param string $language
     * @param string $siteaccess
     *
     * @return array
     */
    protected function generateUriRoute($uri, $language, $siteaccess)
    {
        $routeInfo = $this->router->match($uri);

        $routeInfo['siteaccess'] = $siteaccess;
        $name = $routeInfo['_route'];
        unset($routeInfo['_route']);

        $route = $this->router->generate($name, $routeInfo, true);

        return array(
            'siteaccess' => $siteaccess,
            'languageCode' => $language,
            'url' => $route
        );
    }

    /**
     * Initializes the service by fetching the required services.
     *
     * The services could be injected directly but the service-container is required non the less.
     *
     * @return void
     */
    private function init()
    {
        if ($this->initialized) {
            return;
        }

        $this->routeGenerator = $this->container->get('ezpublish.route_reference.generator');
        $this->contentService = $this->container->get('ezpublish.api.service.content');
        $this->configResolver = $this->container->get('ezpublish.config.resolver');
        $this->router = $this->container->get('router');

        $matcherConfig = $this->container->getParameter('ezpublish.siteaccess.match_config');
        if (isset($matcherConfig['Map\Host'])) {
            $this->fullUrl = true;
        }
    }

}
