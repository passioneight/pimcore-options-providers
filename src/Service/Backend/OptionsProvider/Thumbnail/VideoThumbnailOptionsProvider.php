<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\OptionsProvider\Thumbnail;

use Passioneight\Bundle\PimcoreUtilitiesBundle\Constant\ThumbnailType;

class VideoThumbnailOptionsProvider extends ThumbnailOptionsProvider
{
    /**
     * @inheritDoc
     */
    protected function getThumbnailType()
    {
        return ThumbnailType::VIDEO;
    }
}
