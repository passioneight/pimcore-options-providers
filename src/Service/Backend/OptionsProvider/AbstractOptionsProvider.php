<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\OptionsProvider;

use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Constant\OptionsProviderData;
use Pimcore\Cache\Runtime;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Pimcore\Model\DataObject\ClassDefinition\DynamicOptionsProvider\SelectOptionsProviderInterface;
use Pimcore\Tool;

abstract class AbstractOptionsProvider implements SelectOptionsProviderInterface
{
    const CACHE_KEY_PREFIX = "options-provider_";
    
    /** @var array $configuration */
    protected $configuration;

    /**
     * @inheritDoc
     * In most cases the select has static options, thus, true is the default value. Override if necessary.
     */
    public function hasStaticOptions($context, $fieldDefinition)
    {
        $configuration = $this->loadConfiguration($context, $fieldDefinition);

        $hasStaticOptions = $configuration[OptionsProviderData::STATIC_OPTIONS] ?? null;
        $hasStaticOptions = is_bool($hasStaticOptions) ? $hasStaticOptions : true;

        return $hasStaticOptions;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue($context, $fieldDefinition)
    {
        $configuration = $this->loadConfiguration($context, $fieldDefinition);

        if ($default = $configuration[OptionsProviderData::DEFAULT_VALUE] ?? null) {
            return $default;
        }

        return null;
    }

    /**
     * @param array $context
     * @return string|null
     */
    protected function getFieldName($context)
    {
        return $context[OptionsProviderData::FIELD_NAME];
    }

    /**
     * @param array|null $context
     * @param Data|null $fieldDefinition
     * @return array
     */
    protected function loadConfiguration($context, ?Data $fieldDefinition): array
    {
        $cacheKey = self::CACHE_KEY_PREFIX . md5($fieldDefinition ? $fieldDefinition->getOptionsProviderData() : serialize($context));
        $cacheKey = Tool::getValidCacheKey($cacheKey);

        try{
            $this->configuration = Runtime::get($cacheKey);
        } catch (\Exception $exception) {
            $optionsProviderData = $fieldDefinition ? $fieldDefinition->getOptionsProviderData() : $context;
            $optionsProviderData = is_string($optionsProviderData) && !empty($optionsProviderData) ? json_decode($optionsProviderData, true) : $optionsProviderData;
            $this->configuration = $optionsProviderData ?: [];

            Runtime::set($cacheKey, $this->configuration);

            if(json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException("The options provider data is not valid. Reason: " . json_last_error_msg());
            }
        }

        return $this->configuration;
    }

    /**
     * @param string $name
     * @return string|null the value for the configuration with the given name if available, NULL otherwise.
     */
    protected function getConfiguration(string $name)
    {
        $configuration = $this->configuration ?: [];
        return array_key_exists($name, $configuration) ? $configuration[$name] : null;
    }

    /**
     * Prepares the given options (e.g., to return a certain format).
     * Override as needed.
     *
     * @param array $options
     * @param array|null $context
     * @param Data|null $fieldDefinition
     * @return array
     */
    abstract protected function prepareOptions(array $options, $context, ?Data $fieldDefinition);
}
