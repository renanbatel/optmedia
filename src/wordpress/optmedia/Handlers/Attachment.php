<?php

namespace OptMedia\Handlers;

use DOMDocument;

use OptMedia\Constants;
use OptMedia\Utils\MediaSettings;

class Attachment
{
    /**
     * Get lightest file of the array
     *
     * @param array $files
     * @return array The lightest file information block
     *
     * @since 0.1.2
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function getLightestFile(array $files): array
    {
        return array_reduce($files, function ($prev = null, $current) {
            if ($prev && $prev["fileSize"] <= $current["fileSize"]) {
                return $prev;
            }

            return $current;
        });
    }

    /**
     * Get the size files array from the attachment meta
     *
     * @param array $files The optmedia_files attachment meta
     * @param string $size The size name
     * @return array The size files array
     *
     * @since 0.1.2
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function getSizeFiles(array $files, string $size): array
    {
        return array_reduce($files, function ($carry, $current) use ($size) {
            $carry[] = $current["sizes"][$size];

            return $carry;
        }, []);
    }

    /**
     * Get the image new attributes for lazy load
     *
     * @param array $attributes The image attributes
     * @return array The attributes for lazy load
     *
     * @since 0.1.2
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function getImageLazyLoadAttributes(array $attributes): array
    {
        $attributes["class"] = isset($attributes["class"])
            ? $attributes["class"]
            : "";

        return array_reduce(array_keys($attributes), function ($carry, $key) use ($attributes) {
            if ($key === "class") {
                $carry["class"] = trim("{$attributes[$key]} om-lazy-load");
            } else {
                $carry["data-{$key}"] = $attributes[$key];
            }

            return $carry;
        }, []);
    }

    /**
     * Get the inner HTML of the body
     *
     * @param string $html The html document
     * @return string The body inner html
     */
    public function getBodyInnerHTML(string $html): string
    {
        $bodyOpen = "<body>";
        $bodyClose = "</body>";
        $withoutHead = substr($html, (strpos($html, $bodyOpen) + strlen($bodyOpen)));
        $withoutFoot = substr($withoutHead, 0, strpos($withoutHead, $bodyClose));

        return trim($withoutFoot);
    }

    /**
     * Calculates the lightest file for each image size
     *
     * @param array $sources The image sizes sources array
     * @param array $sizes An image sizes array
     * @param string $src The image src url
     * @param array $meta Image meta
     * @param integer $attachmentId
     * @return array The image sizes sources array
     *
     * @since 0.1.2
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function handleImageSrcsetCalculation(
        array $sources,
        array $sizes,
        string $src,
        array $meta,
        int $attachmentId
    ): array {
        $mediaSettings = new MediaSettings();
        $files = get_post_meta($attachmentId, Constants::ATTACHMENT_META_FILES, true);

        foreach ($sources as $key => $source) {
            $size = $mediaSettings->getImageSizeNameByDimension(
                $source["descriptor"],
                $source["value"]
            );
            $lightest = $this->getLightestFile($this->getSizeFiles($files, $size));
            $sources[$key]["url"] = $lightest["url"];
        }

        return $sources;
    }

    /**
     * Filters post content for image attributes insertion
     *
     * @param string $content The post content
     * @return string The filtered post content
     *
     * @since 0.1.2
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function handlePostContent(string $content): string
    {
        $imageAttributes = [
            "src",
            "srcset",
            "sizes",
            "class",
        ];
        $html = new DOMDocument();
        
        libxml_use_internal_errors(true);

        $html->loadHTML($content);
        
        $images = $html->getElementsByTagName("img");

        foreach ($images as $image) {
            $attributes = [];
            
            foreach ($imageAttributes as $imageAttribute) {
                if ($image->hasAttribute($imageAttribute)) {
                    $attributes[$imageAttribute] = $image->getAttribute($imageAttribute);
    
                    $image->removeAttribute($imageAttribute);
                }
            }

            $lazyLoadAttributes = $this->getImageLazyLoadAttributes($attributes);

            foreach ($lazyLoadAttributes as $key => $value) {
                $image->setAttribute($key, $value);
            }
        }

        libxml_use_internal_errors(false);

        return $this->getBodyInnerHTML($html->saveHTML());
    }
}
