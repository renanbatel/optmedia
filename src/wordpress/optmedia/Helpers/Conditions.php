<?php

/**
 * Class Conditions
 *
 * This class has some helper functions to check for specif conditions
 */

namespace OptMedia\Helpers;

class Conditions
{

    /**
     * Check if a mime type is for videos
     *
     * @param string $mimeType The mime type to be checked
     * @return boolean True if it's a video mime type, false if it's not
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function isVideoMimeType($mimeType)
    {
        return preg_match("/^video\//", $mimeType);
    }

    /**
     * Check if a mime type is for images
     *
     * @param string $mimeType The mime type to be checked
     * @return boolean True if it's a image mime type, false if it's not
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function isImageMimeType($mimeType)
    {
        return preg_match("/^image\//", $mimeType);
    }
}
