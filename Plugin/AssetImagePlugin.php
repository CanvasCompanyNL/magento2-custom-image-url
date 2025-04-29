<?php

namespace CanvasCompanyNL\CustomImageUrl\Plugin;

use CanvasCompanyNL\CustomImageUrl\Helper\Data;
use Magento\Catalog\Model\View\Asset\Image;
use Magento\Framework\Exception\LocalizedException;

readonly class AssetImagePlugin
{
    /**
     * @param Data $helper
     */
    public function __construct(private Data $helper)
    {
    }

    /**
     * Change the custom URL
     *
     * @return string
     * @throws LocalizedException
     */
    public function afterGetUrl(Image $subject, $result)
    {
        return $this->helper->replaceImageUrlWithCustom($subject, $result);
    }
}
