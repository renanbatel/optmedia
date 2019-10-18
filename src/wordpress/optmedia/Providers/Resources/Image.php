<?php

/**
 * Interface Image
 *
 * Defines an Image Provider class implementation
 */

namespace OptMedia\Providers\Resources;

interface Image
{
    /**
     * Creates the Image Provider for the image with all the Provider
     * Interfaces implementations
     *
     * @param string $file The image file path
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function __construct($file);
}
