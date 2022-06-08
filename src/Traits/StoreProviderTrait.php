<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Traits;

use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\StoreProvider\StoreProvider;

trait StoreProviderTrait
{
    protected StoreProvider $storeProvider;

    /**
     * @required
     * @param StoreProvider $storeProvider
     * @internal
     */
    public function setStoreProvider(StoreProvider $storeProvider)
    {
        $this->storeProvider = $storeProvider;
    }
}
