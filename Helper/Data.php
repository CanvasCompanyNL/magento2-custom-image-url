<?php

namespace CanvasCompanyNL\CustomImageUrl\Helper;

use CanvasCompanyNL\CustomImageUrl\Model\Config\CustomConfig;
use Magento\Catalog\Model\Config\CatalogMediaConfig;
use Magento\Catalog\Model\View\Asset\Image;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use RuntimeException;

class Data extends AbstractHelper
{
    public function __construct(
        Context $context,
        private readonly CatalogMediaConfig $catalogMediaConfig,
        private readonly CustomConfig $customConfig,
        private readonly StoreManagerInterface $storeManager,
    ) {
        parent::__construct($context);
    }

    /**
     * @param Image $image
     * @param string $imageUrl
     * @return string
     * @throws LocalizedException
     */
    public function replaceImageUrlWithCustom(Image $image, string $imageUrl): string
    {
        $customType = $this->getCustomUrlType();

        // No action required, just use the default URL
        if ($customType === CustomConfig::TYPE_DEFAULT) {
            return $imageUrl;
        }

        $params = $image->getImageTransformationParameters();
        $params['file_path'] = $image->getFilePath();

        if ($customType === CustomConfig::TYPE_PATTERN) {
            return $this->getCustomUrlFromPattern($imageUrl, $params);
        }

        if ($customType === CustomConfig::TYPE_IMGPROXY) {
            return $this->getImgProxyUrl($imageUrl, $params);
        }

        throw new LocalizedException(
            __("The specified Custom Catalog media URL type '$customType' is not supported."),
        );
    }

    /**
     * @param string $imageUrl
     * @param array $params
     * @return string
     */
    public function getCustomUrlFromPattern(string $imageUrl, array $params): string
    {
        $customUrl = $this->customConfig->getCustomPattern();

        $urlParts = parse_url($imageUrl);
        foreach ($urlParts as $part => $value) {
            $params['url_' . $part] = $value;
        }

        $params['path'] = ltrim((string) $params['url_path'], '/');
        $params['base_url'] = $this->getBaseUrl();
        $params['base_url_media'] = $this->getMediaBaseUrl();
        $params['image_url'] = explode('?', $imageUrl)[0];

        foreach ($params as $k => $v) {
            $k = str_replace('-', '_', $k);
            if (str_contains($customUrl, '{{' . $k)) {
                $customUrl = str_replace(
                    ['{{' . $k . '}}', '{{' . $k . '|urlencode}}', '{{' . $k . '|rawurlencode}}', '{{' . $k . '|base64}}'],
                    [$v, urlencode((string) $v), rawurlencode((string) $v), base64_encode((string) $v)],
                    $customUrl,
                );
            }
        }

        return $customUrl;
    }

    /**
     * Based on https://github.com/imgproxy/imgproxy/blob/master/examples/signature.php
     *
     * @param string $imageUrl
     * @param array $params
     * @return string
     */
    public function getImgProxyUrl(string $imageUrl, array $params): string
    {
        $resize = $this->customConfig->getImgproxyResize();
        $width = $params['width'];
        $height = $params['height'];

        $urlParts = parse_url($imageUrl);
        $path = ltrim($urlParts['path'], '/');
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($this->customConfig->getImgproxySourceType() === CustomConfig::IMGPROXY_S3) {
            $prefix = trim($this->customConfig->getImgproxySourcePrefix(), '/');
            if ($prefix === '') {
                throw new RuntimeException('S3 Bucket cannot be empty');
            }
            $sourceUrl = 's3://' . $prefix . '/' . $path;
        } elseif ($this->customConfig->getImgproxySourceType() === CustomConfig::IMGPROXY_LOCAL) {
            $prefix = trim($this->customConfig->getImgproxySourcePrefix(), '/');
            if ($prefix !== '') {
                $path = $prefix . '/' . $path;
            }
            $sourceUrl = 'local:///' . $path;
        } elseif ($this->customConfig->getImgproxySourceType() === CustomConfig::IMGPROXY_CUSTOM_URL) {
            $proxyUrl = trim($this->customConfig->getImgproxySourceCustomUrl(), '/');
            $sourceUrl = $proxyUrl . '/' . $path;
        } else {
            $sourceUrl = $imageUrl;
        }

        $encodedUrl = rtrim(strtr(base64_encode($sourceUrl), '+/', '-_'), '=');
        $path = "/resize:{$resize}:$width:$height/{$encodedUrl}.{$extension}";

        // Sign the URL
        $key = $this->customConfig->getImgproxyKey();
        $salt = $this->customConfig->getImgproxySalt();
        if ($key !== '' && $salt !== '') {
            $path = $this->signImgproxyPath($path, $key, $salt);
        } else {
            $path = '/insecure' . $path;
        }

        return rtrim($this->customConfig->getImgproxyHost(), '/') . $path;
    }

    /**
     * @param string $path
     * @param string $key
     * @param string $salt
     * @return string
     * @throws RuntimeException
     */
    private function signImgproxyPath(string $path, string $key, string $salt): string
    {
        $keyBin = pack("H*", $key);
        if (empty($keyBin)) {
            throw new RuntimeException('Key expected to be hex-encoded string');
        }

        $saltBin = pack("H*", $salt);
        if (empty($saltBin)) {
            throw new RuntimeException('Salt expected to be hex-encoded string');
        }

        $signature = rtrim(strtr(base64_encode(hash_hmac('sha256', $saltBin . $path, $keyBin, true)), '+/', '-_'), '=');

        return sprintf("/%s%s", $signature, $path);
    }

    /**
     * @return string|null
     */
    private function getCustomUrlType(): ?string
    {
        // When Dynamic hashing is disabled, return false
        if ($this->catalogMediaConfig->getMediaUrlFormat(ScopeInterface::SCOPE_STORE) !== CatalogMediaConfig::IMAGE_OPTIMIZATION_PARAMETERS) {
            return CustomConfig::TYPE_DEFAULT;
        }

        return $this->customConfig->getCustomType();
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    private function getBaseUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    private function getMediaBaseUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }
}
