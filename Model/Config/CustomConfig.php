<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CanvasCompanyNL\CustomImageUrl\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Config for catalog media
 */
class CustomConfig
{
    public const string XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_TYPE = 'web/url/catalog_media_url_format_custom_type';
    public const string XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_PATTERN = 'web/url/catalog_media_url_format_custom_pattern';
    public const string XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_HOST = 'web/url/catalog_media_url_format_custom_imgproxy_host';
    public const string XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_RESIZE = 'web/url/catalog_media_url_format_custom_imgproxy_resize';

    public const string XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_KEY = 'web/url/catalog_media_url_format_custom_imgproxy_key';
    public const string XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_SALT = 'web/url/catalog_media_url_format_custom_imgproxy_salt';

    public const string XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_SOURCE_TYPE = 'web/url/catalog_media_url_format_custom_imgproxy_source_type';
    public const string XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_SOURCE_PREFIX = 'web/url/catalog_media_url_format_custom_imgproxy_source_prefix';
    public const string XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_SOURCE_CUSTOM_URL = 'web/url/catalog_media_url_format_custom_imgproxy_source_custom_url';

    public const string TYPE_DEFAULT = 'default';
    public const string TYPE_PATTERN = 'pattern';
    public const string TYPE_IMGPROXY = 'imgproxy';

    public const string IMGPROXY_URL = 'url';
    public const string IMGPROXY_CUSTOM_URL = 'custom_url';
    public const string IMGPROXY_LOCAL = 'local';
    public const string IMGPROXY_S3 = 's3';

    public const string IMGPROXY_FIT = 'fit';
    public const string IMGPROXY_FILL = 'fill';
    public const string IMGPROXY_AUTO = 'auto';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(private readonly ScopeConfigInterface $scopeConfig)
    {
    }

    /**
     * Get the custom types
     *
     * @return string
     */
    public function getCustomType(): string
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_TYPE,
            ScopeInterface::SCOPE_STORE,
        );

        if ($value === null) {
            return self::TYPE_DEFAULT;
        }

        return (string)$value;
    }

    public function getImgproxyHost(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_HOST,
            ScopeInterface::SCOPE_STORE,
        );
    }

    public function getImgproxyResize(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_RESIZE,
            ScopeInterface::SCOPE_STORE,
        ) ?: '';
    }

    public function getImgproxySourceType(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_SOURCE_TYPE,
            ScopeInterface::SCOPE_STORE,
        );
    }

    public function getImgproxySourcePrefix(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_SOURCE_PREFIX,
            ScopeInterface::SCOPE_STORE,
        ) ?: '';
    }

    public function getImgproxySourceCustomUrl(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_SOURCE_CUSTOM_URL,
            ScopeInterface::SCOPE_STORE,
        ) ?: '';
    }

    public function getImgproxyKey(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_KEY,
            ScopeInterface::SCOPE_STORE,
        ) ?: '';
    }

    public function getImgproxySalt(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_IMGPROXY_SALT,
            ScopeInterface::SCOPE_STORE,
        ) ?: '';
    }

    public function getCustomPattern(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CATALOG_MEDIA_URL_FORMAT_CUSTOM_PATTERN,
            ScopeInterface::SCOPE_STORE,
        );
    }
}
