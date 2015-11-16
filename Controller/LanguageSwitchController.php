<?php
/**
 * @category   PHP
 * @package    intense-programming
 * @version    1
 * @date       28/07/2015 00:57
 * @author     Konrad, Steve <skonrad@wingmail.net>
 * @copyright  2015, IntenseProgramming
 */

namespace IntenseProgramming\LanguageSwitcherBundle\Controller;

use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\MVC\Symfony\Controller\Controller;
use IntenseProgramming\LanguageSwitcherBundle\Services\UrlGeneratorService;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LanguageSwitchController.
 *
 * @package   IntenseProgramming\LanguageSwitcherBundle\Controller
 * @author    Konrad, Steve <skonrad@wingmail.net>
 * @copyright 2015, IntenseProgramming
 */
class LanguageSwitchController extends Controller
{

    /**
     * Renders the language-switcher.
     *
     * @param Request         $request
     * @param string|Location $translationParameter
     * @param array           $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generateAction(Request $request, $translationParameter, $parameters = array())
    {
        /** @var UrlGeneratorService $urlGenerator */
        $urlGenerator = $this->container->get('intense.programming.language.switcher');
        $template = $this->container->getParameter('intense.programming.language.switcher.template');

        return $this->render(
            $template,
            $urlGenerator->generateUrls($translationParameter, $request, $parameters)
        );
    }

}
