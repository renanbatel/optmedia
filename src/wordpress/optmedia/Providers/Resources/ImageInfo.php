<?php

/**
 * Interface ImageInfo
 *
 * Defines an Image Information Provider class implementation,
 * this class is used for getting image file specific information
 *  e.g. The image file size in bytes
 */

namespace OptMedia\Providers\Resources;

interface ImageInfo
{
    /**
     * Creates the Image Information Provider
     *
     * @param string $file The image file path
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function __construct($file);

    /**
     * Gets the image sizes
     *
     * @return array The image sizes
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function getSizes();

    /**
     * Gets the image file size in bytes
     *
     * @return int Image file size in bytes
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function getFileSizeInBytes();
}
