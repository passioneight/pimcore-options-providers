<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\OptionsProvider\Thumbnail;

use Passioneight\Bundle\PimcoreUtilitiesBundle\Constant\ThumbnailType;

class ImageThumbnailOptionsProvider extends ThumbnailOptionsProvider
{
    /**
     * @inheritDoc
     */
    protected function getThumbnailType()
    {
        return ThumbnailType::IMAGE;
    }
}
