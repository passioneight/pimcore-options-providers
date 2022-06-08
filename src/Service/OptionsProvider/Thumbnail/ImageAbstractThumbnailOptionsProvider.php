<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\OptionsProvider\Thumbnail;

use Passioneight\Bundle\PimcoreUtilitiesBundle\Constant\ThumbnailType;

class ImageAbstractThumbnailOptionsProvider extends AbstractThumbnailOptionsProvider
{
    /**
     * @inheritDoc
     */
    protected function getThumbnailType(): string
    {
        return ThumbnailType::IMAGE;
    }
}
