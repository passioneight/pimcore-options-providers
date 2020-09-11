<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\OptionsProvider\Thumbnail;

use Passioneight\Bundle\PimcoreUtilitiesBundle\Constant\ThumbnailType;
use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\Backend\OptionsProvider\AbstractOptionsProvider;
use Pimcore\Model\Asset\Image\Thumbnail\Config as ImageThumbnail;
use Pimcore\Model\Asset\Video\Thumbnail\Config as VideoThumbnail;
use Pimcore\Model\DataObject\ClassDefinition\Data;

abstract class ThumbnailOptionsProvider extends AbstractOptionsProvider
{
    /**
     * Neither $context nor $fieldDefinition are used here. Thus, no need to pass any values.
     *
     * @inheritDoc
     */
    public function getOptions($context = null, $fieldDefinition = null)
    {
        /** @var ImageThumbnail\Listing|VideoThumbnail\Listing $thumbnails */
        $thumbnails = $this->loadThumbnails($context, $fieldDefinition);
        $thumbnails = $thumbnails->getThumbnails();

        $options = $this->prepareOptions($thumbnails, $context, $fieldDefinition);
        return array_values($options);
    }

    /**
     * @inheritDoc
     */
    protected function prepareOptions(array $thumbnails, $context, $fieldDefinition)
    {
        $options = [];

        foreach ($thumbnails as $thumbnail) {
            $thumbnailName = $thumbnail->getName();

            $options[] = [
                "key" => $thumbnailName,
                "value" => $thumbnailName,
            ];
        }

        return $options;
    }

    /**
     * @param array|null $context
     * @param Data|null $fieldDefinition
     * @return ImageThumbnail\Listing|ImageThumbnail\Listing
     */
    private function loadThumbnails(?array $context, ?Data $fieldDefinition)
    {
        $this->loadConfiguration($context, $fieldDefinition);

        $thumbnails = [];

        if ($this->getThumbnailType() === ThumbnailType::IMAGE) {
            $thumbnails = new ImageThumbnail\Listing();
        } else if ($this->getThumbnailType() === ThumbnailType::VIDEO) {
            $thumbnails = new ImageThumbnail\Listing();
        }

        return $thumbnails;
    }

    /**
     * @return string @see{ThumbnailType}
     */
    abstract protected function getThumbnailType();

    /**
     * @inheritDoc
     */
    public function hasStaticOptions($context, $fieldDefinition)
    {
        return false;
    }
}
