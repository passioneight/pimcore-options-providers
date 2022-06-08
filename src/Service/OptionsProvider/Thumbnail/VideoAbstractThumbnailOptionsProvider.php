<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\OptionsProvider\Thumbnail;

use Passioneight\Bundle\PimcoreUtilitiesBundle\Constant\ThumbnailType;

class VideoAbstractThumbnailOptionsProvider extends AbstractThumbnailOptionsProvider
{
    /**
     * @inheritDoc
     */
    protected function getThumbnailType(): string
    {
        return ThumbnailType::VIDEO;
    }
}
