# Magento2 Custom Image Url

Magento 2.4 has the option to set a dynamic URL (General -> Web -> Url options). 
This module adds an extra option to define the format of the URL.

## Install

```
composer require CanvasCompanyNL/magento2-custom-image-url
php bin/magento module:enable CanvasCompanyNL_CustomImageUrl
php bin/magento setup:upgrade
```

## Configuration

You can configure the url in General -> Web -> Url Options. Set the 'Catalog media URL format' option to 'Image optimization based on query parameters'. 
This will prevent Magento from creating resized images. Select a Custom URL format for Imgproxy or Pattern, to use this module.

![image](https://user-images.githubusercontent.com/973269/108608605-6cbe5b00-73c8-11eb-90dd-37777dc5d64c.png)


### Pattern

Provide a pattern to replace the URL with:

Example https://example.com/{{path}}?w={{width}}&h={{height}}

The available filters (for urlencode etc) and variables are described in the config.

### Imgproxy

![image](https://user-images.githubusercontent.com/973269/108608636-94152800-73c8-11eb-88bc-da2d6b4ed4c0.png)


Use the Imgproxy format, either with URL, S3 or Local files. You can provide a key/hash to sign the URLs

## License

MIT
