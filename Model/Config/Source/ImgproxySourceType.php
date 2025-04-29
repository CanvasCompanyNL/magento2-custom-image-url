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
class ImgproxySourceType implements OptionSourceInterface
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
                'value' => CustomConfig::IMGPROXY_URL,
                'label' => __('URL: Proxy the default URL'),
            ],
            [
                'value' => CustomConfig::IMGPROXY_CUSTOM_URL,
                'label' => __('Custom URL: Proxy an url other than the default URL'),
            ],
            [
                'value' => CustomConfig::IMGPROXY_LOCAL,
                'label' => __('Local: Pass the local path'),
            ],
            [
                'value' => CustomConfig::IMGPROXY_S3,
                'label' => __('S3: Provide an S3 bucket'),
            ],
        ];
    }
}
