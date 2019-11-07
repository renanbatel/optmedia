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
}
