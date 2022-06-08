<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Traits;

use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\OptionsProvider\ConstantOptionsProvider;

trait ConstantOptionsProviderTrait
{
    protected ConstantOptionsProvider $constantOptionsProvider;

    /**
     * @required
     * @param ConstantOptionsProvider $constantOptionsProvider
     * @internal
     */
    public function setConstantOptionsProvider(ConstantOptionsProvider $constantOptionsProvider)
    {
        $this->constantOptionsProvider = $constantOptionsProvider;
    }
}
