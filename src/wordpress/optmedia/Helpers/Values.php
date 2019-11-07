<?php

/**
 * Class Values
 *
 * This class has some helper functions for getting or converting specific values
 */

namespace OptMedia\Helpers;

class Values
{
    /**
     * Gets the file extension from its mime type
     *
     * @param string $mimeType The mime type
     * @return string
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function getExtensionFromMimeType($mimeType): string
    {
        return explode("/", $mimeType)[1];
    }

    /**
     * Builds the file information block
     *
     * @param string $file The path
     * @param mixed $width
     * @param mixed $height
     * @return array The file information block
     *
     * @since 0.1.1
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public static function buildFileInformation(string $file, $width, $height): array
    {
        $uploadDir = wp_upload_dir(null, false);
        $url = "{$uploadDir["url"]}/" . basename($file);
        $fileSize = filesize($file);

        return compact(
            "file",
            "url",
            "fileSize",
            "width",
            "height"
        );
    }
}
