<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\OptionsProvider;

use Passioneight\Bundle\PhpUtilitiesBundle\Service\Utility\PathUtility;
use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Constant\OptionsProviderData;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

class FileOptionsProvider extends AbstractOptionsProvider
{
    /**
     * Convenience method; in case the options are needed programmatically.
     */
    public function getOptionsForPath(string $path): array
    {
        $context = [
            OptionsProviderData::PATH => $path
        ];

        return $this->getOptions($context, null);
    }

    /**
     * @inheritDoc
     */
    public function getOptions($context, $fieldDefinition): array
    {
        $this->loadConfiguration($context, $fieldDefinition);
        $path = $this->loadPath($context, $fieldDefinition);

        $finder = new Finder();

        try {
            $finder
                ->files()
                ->in($path);

            $options = iterator_to_array($finder);
        } catch (DirectoryNotFoundException $e) {
            $options = [];
        }

        $options = $this->prepareOptions($options, $context, $fieldDefinition);

        return array_values($options);
    }

    /**
     * @inheritDoc
     *
     * Note that we don't need to actually translate the option's label, because Pimcore translates all labels
     * automatically anyway.
     */
    protected function prepareOptions(array $options, $context, ?Data $fieldDefinition): array
    {
        /** @var \SplFileInfo $option */
        foreach ($options as $key => $option) {
            $options[$key] = [
                "key" => $option->getBasename('.' . $option->getExtension()),
                "value" => $option->getRealPath(),
            ];
        }

        return $options;
    }

    protected function loadPath($context, ?Data $fieldDefinition): string
    {
        $this->loadConfiguration($context, $fieldDefinition);
        return $this->getConfiguration(OptionsProviderData::PATH) ?: $this->getDefaultPath();
    }

    public static function getDefaultPath(): string
    {
        return PathUtility::join(PIMCORE_PROJECT_ROOT, "assets", "images", "icons", "social-media");
    }
}
