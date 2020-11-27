<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\OptionsProvider;

use Passioneight\Bundle\PhpUtilitiesBundle\Constant\Constant;
use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Constant\OptionsProviderData;
use Passioneight\Bundle\PhpUtilitiesBundle\Constant\Php;
use Passioneight\Bundle\PimcoreUtilitiesBundle\Constant\TranslatableConstant;
use Passioneight\Bundle\PhpUtilitiesBundle\Service\Utility\NamespaceUtility;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConstantOptionsProvider extends AbstractOptionsProvider
{
    const DEFAULT_CONSTANTS_NAMESPACE = "AppBundle" . Php::NAMESPACE_DELIMITER . "Constant";

    /** @var TranslatorInterface $translator */
    private $translator;

    /**
     * Convenience method; in case the options are needed programmatically.
     *
     * @param string $constantsClass fully qualified namespace
     * @return array
     */
    public function getOptionsForConstant(string $constantsClass)
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
     */
    protected function prepareOptions(array $options, $context, ?Data $fieldDefinition)
    {
        $class = $this->loadConstantsClass($context, $fieldDefinition);
        $isTranslatable = is_a($class, TranslatableConstant::class, true);

        foreach ($options as $key => $option) {
            $options[$key] = [
                "key" => $option,
                "value" => $option,
            ];

            if ($isTranslatable) {
                $translationKey = $class::toTranslationKey($option);
                $options[$key]["key"] = $this->translator->trans($translationKey);
            }
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
    protected function getFQCN(string $class)
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
     * Only implemented for convenience, as this method allows overriding the default namespace for the constants class.
     * @return string
     */
    protected function getDefaultConstantsNamespace()
    {
        return self::DEFAULT_CONSTANTS_NAMESPACE;
    }

    /**
     * @required
     * @internal
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
}
