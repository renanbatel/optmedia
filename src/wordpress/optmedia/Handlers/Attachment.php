<?php

namespace OptMedia\Handlers;

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
     * Undocumented function
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
}
