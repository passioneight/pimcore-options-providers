<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\StoreProvider;

use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\OptionsProvider\AbstractOptionsProvider;
use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Traits\ConstantOptionsProviderTrait;
use Pimcore\Model\DataObject\ClassDefinition\Data;

class StoreProvider
{
    use ConstantOptionsProviderTrait;

    /**
     * Convenience method to directly pass a constant class.
     *
     * @param string $constantsClass
     * @return array
     */
    public function getStoreForConstant(string $constantsClass): array
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
    public function getStoreForOptionsProvider(AbstractOptionsProvider $optionsProvider, $optionsProviderContext = null, $optionsProviderFieldDefinition = null): array
    {
        $options = $optionsProvider->getOptions($optionsProviderContext, $optionsProviderFieldDefinition);
        return $this->getStore($options);
    }

    /**
     * @param array $optionsProviderOptions
     * @return array
     */
    protected function getStore(array $optionsProviderOptions): array
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
}
