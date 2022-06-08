<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\OptionsProvider;

use Passioneight\Bundle\PhpUtilitiesBundle\Constant\Constant;
use Passioneight\Bundle\PhpUtilitiesBundle\Constant\Php;
use Passioneight\Bundle\PhpUtilitiesBundle\Service\Utility\NamespaceUtility;
use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Constant\OptionsProviderData;
use Passioneight\Bundle\PimcoreUtilitiesBundle\Constant\TranslatableConstant;
use Pimcore\Model\DataObject\ClassDefinition\Data;

class ConstantOptionsProvider extends AbstractOptionsProvider
{
    /**
     * Convenience method; in case the options are needed programmatically.
     *
     * @param string $constantsClass fully qualified namespace
     * @return array
     */
    public function getOptionsForConstant(string $constantsClass): array
    {
        $context = [
            OptionsProviderData::CONSTANTS_CLASS => $constantsClass
        ];

        return $this->getOptions($context, null);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions($context, $fieldDefinition)
    {
        $this->loadConfiguration($context, $fieldDefinition);

        $class = $this->loadConstantsClass($context, $fieldDefinition);

        $options = $class::getAll();
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
        $class = $this->loadConstantsClass($context, $fieldDefinition);
        $isTranslatable = is_a($class, TranslatableConstant::class, true);

        foreach ($options as $key => $option) {
            $options[$key] = [
                "key" => $isTranslatable ? $class::toTranslationKey($option) : $option,
                "value" => $option,
            ];
        }

        return $options;
    }

    /**
     * @param array|null $context
     * @param Data|null $fieldDefinition
     * @return string|Constant|TranslatableConstant the actual return value is a string; any other type hint is for IDE auto-completion.
     */
    protected function loadConstantsClass($context, ?Data $fieldDefinition): string
    {
        $this->loadConfiguration($context, $fieldDefinition);

        $constantsClass = $this->getConfiguration(OptionsProviderData::CONSTANTS_CLASS);
        $constantsClass = $constantsClass ?: $this->getConstantsClassFromFieldName($context);

        return $this->getFQCN($constantsClass);
    }

    /**
     * @param array|null $context
     * @return string the constants class (without namespace) based on the field name.
     */
    protected function getConstantsClassFromFieldName($context): string
    {
        return ucfirst($this->getFieldName($context));
    }

    /**
     * @param string $class
     * @return string the fully qualified class name.
     */
    protected function getFQCN(string $class): string
    {
        $namespace = NamespaceUtility::getNamespaceForClass($class);

        if (empty($namespace)) {
            $namespace = $this->getDefaultConstantsNamespace();
        } else {
            $class = NamespaceUtility::getClassNameFromNamespace($class);
        }

        return join(Php::NAMESPACE_DELIMITER, [$namespace, $class]);
    }

    /**
     * @return string
     */
    public static function getDefaultConstantsNamespace(): string
    {
        return NamespaceUtility::join("App", "Constant");
    }
}
