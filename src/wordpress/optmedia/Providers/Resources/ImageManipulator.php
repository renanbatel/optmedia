<?php

/**
 * Interface ImageManipulator
 *
 * Defines an Image Manipulation Provider class implementation,
 * this class is used for manipulating a image
 *  e.g. convert an image to a different format
 */

namespace OptMedia\Providers\Resources;

interface ImageManipulator
{

    /**
     * Creates the Image Manipulation Provider
     *
     * @param string $file The image file path
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function __construct($file);

    /**
     * Converts the image to a specific format
     *
     * @param string $format The format to be converted
     * @param string $outputDir The converted image output directory
     * @return string The converted image file path
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function convert($format, $outputDir = "");

    /**
     * Resize the image to a specific resolution
     *
     * @param string $width The width to be resized in pixels
     * @param string $height The height to be resided in pixels
     * @param boolean $cut If the image will be cut to match the width and height
     * @param string $outputDir The converted image output directory
     * @return string The resized image file path if it was resized, null if it wasn't
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function resize($width, $height, $cut = false, $outputDir = "");
}
