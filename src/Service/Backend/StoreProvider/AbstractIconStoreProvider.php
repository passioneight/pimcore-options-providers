<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\StoreProvider;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractIconStoreProvider extends StoreProvider
{
    /** @var Environment $environment */
    protected $environment;

    /**
     * @param array $optionsProviderOptions
     * @return array
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function getStore(array $optionsProviderOptions)
    {
        $options = [];
        foreach ($optionsProviderOptions as $optionsProviderOption) {
            $options[] = [
                $optionsProviderOption["value"],
                $this->renderIconTemplate($optionsProviderOption["key"])
            ];
        }

        return $options;
    }

    /**
     * @param string $icon
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function renderIconTemplate(string $icon)
    {
        p_r($this->getIconTemplate());
        p_r($this->environment->render($this->getIconTemplate(), ['icon' => $icon]));
        return $this->environment->render($this->getIconTemplate(), ['icon' => $icon]);
    }

    /**
     * @return string the template to render.
     */
    abstract protected function getIconTemplate(): string;

    /**
     * @required
     * @internal
     *
     * @param Environment $environment
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }
}
