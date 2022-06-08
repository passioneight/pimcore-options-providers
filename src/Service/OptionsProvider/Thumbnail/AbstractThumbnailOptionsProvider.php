<?php

namespace Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\OptionsProvider\Thumbnail;

use Passioneight\Bundle\PimcoreOptionsProvidersBundle\Service\OptionsProvider\AbstractOptionsProvider;
use Passioneight\Bundle\PimcoreUtilitiesBundle\Constant\ThumbnailType;
use Pimcore\Model\Asset\Image\Thumbnail\Config as ImageThumbnail;
use Pimcore\Model\Asset\Video\Thumbnail\Config as VideoThumbnail;
use Pimcore\Model\DataObject\ClassDefinition\Data;

abstract class AbstractThumbnailOptionsProvider extends AbstractOptionsProvider
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
    protected function prepareOptions(array $thumbnails, $context, $fieldDefinition): array
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
     * @return array|ImageThumbnail\Listing|VideoThumbnail\Listing array is returned in case of invalid type
     */
    private function loadThumbnails(?array $context, ?Data $fieldDefinition): array|ImageThumbnail\Listing|VideoThumbnail\Listing
    {
        $this->loadConfiguration($context, $fieldDefinition);

        return match ($this->getThumbnailType()) {
            ThumbnailType::IMAGE => new ImageThumbnail\Listing(),
            ThumbnailType::VIDEO => new VideoThumbnail\Listing(),
            default => []
        };
    }

    /**
     * @return string @see{ThumbnailType}
     */
    abstract protected function getThumbnailType(): string;

    /**
     * @inheritDoc
     */
    public function hasStaticOptions($context, $fieldDefinition)
    {
        return false;
    }
}
