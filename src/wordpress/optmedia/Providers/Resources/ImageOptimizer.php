<?php

/**
 * Interface ImageOptimizer
 *
 * Defines an Image Optimization Provider class implementation,
 * this class is used for optimizing an image
 */

namespace OptMedia\Providers\Resources;

interface ImageOptimizer
{

    /**
     * Creates the Image Optimization Provider
     *
     * @param string $file The image file path
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function __construct($file);

    /**
     * Optimized the image
     *
     * @param string $outputDir The optimized image output directory
     * @return string The optimized image file path
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function optimize($outputDir = "");
}
