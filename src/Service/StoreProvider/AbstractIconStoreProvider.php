<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\StoreProvider;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractIconStoreProvider extends StoreProvider
{
    protected Environment $environment;

    /**
     * @param array $optionsProviderOptions
     * @return array
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function getStore(array $optionsProviderOptions): array
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
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function renderIconTemplate(string $icon): string
    {
        return $this->environment->render($this->getIconTemplate(), [
            'icon' => $icon
        ]);
    }

    /**
     * @return string the template to render.
     */
    abstract protected function getIconTemplate(): string;

    /**
     * @required
     * @param Environment $environment
     * @internal
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }
}
