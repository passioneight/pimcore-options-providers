<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\StoreProvider;

use Passioneight\Bundle\PhpUtilitiesBundle\Constant\Constant;
use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\OptionsProvider\AbstractOptionsProvider;
use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\OptionsProvider\ConstantOptionsProvider;
use Pimcore\Model\DataObject\ClassDefinition\Data;

class StoreProvider
{
    /** @var ConstantOptionsProvider $constantOptionsProvider */
    protected $constantOptionsProvider;

    /**
     * In case no options-provider is in use.
     *
     * @param string|Constant $constantsClass
     * @return array
     */
    public function getStoreForConstant(string $constantsClass)
    {
        $options = $this->constantOptionsProvider->getOptionsForConstant($constantsClass);
        return $this->getStore($options);
    }

    /**
     * @param AbstractOptionsProvider $optionsProvider
     * @param array|null $optionsProviderContext
     * @param Data|null $optionsProviderFieldDefinition
     * @return array
     */
    public function getStoreForOptionsProvider(AbstractOptionsProvider $optionsProvider, $optionsProviderContext = null, $optionsProviderFieldDefinition = null)
    {
        $options = $optionsProvider->getOptions($optionsProviderContext, $optionsProviderFieldDefinition);
        return $this->getStore($options);
    }

    /**
     * @param array $optionsProviderOptions
     * @return array
     */
    protected function getStore(array $optionsProviderOptions)
    {
        $options = [];
        foreach ($optionsProviderOptions as $optionsProviderOption) {
            $options[] = [
                $optionsProviderOption["value"],
                $optionsProviderOption["key"]
            ];
        }

        return $options;
    }

    /**
     * @required
     * @internal
     * @param ConstantOptionsProvider $constantOptionsProvider
     */
    public function setConstantOptionsProvider(ConstantOptionsProvider $constantOptionsProvider)
    {
        $this->constantOptionsProvider = $constantOptionsProvider;
    }
}
