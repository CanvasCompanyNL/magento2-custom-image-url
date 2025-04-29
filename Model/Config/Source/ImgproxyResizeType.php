<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CanvasCompanyNL\CustomImageUrl\Model\Config\Source;

use CanvasCompanyNL\CustomImageUrl\Model\Config\CustomConfig;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Option provider for custom media URL type
 */
class ImgproxyResizeType implements OptionSourceInterface
{
    /**
     * Get a list of supported catalog media URL formats.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => CustomConfig::IMGPROXY_FIT,
                'label' => __('Fit; resizes the image while keeping aspect ratio to fit given size (Default)'),
            ],
            [
                'value' => CustomConfig::IMGPROXY_FILL,
                'label' => __('Fill; resizes the image while keeping aspect ratio to fill given size and cropping projecting parts;'),
            ],
            [
                'value' => CustomConfig::IMGPROXY_AUTO,
                'label' => __('Auto; if both source and resulting dimensions have the same orientation (portrait or landscape), imgproxy will use fill. Otherwise, it will use fit'),
            ],
        ];
    }
}
