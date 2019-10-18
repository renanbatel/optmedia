<?php

/**
 * Class Filename
 *
 * This class has some utilities for filenames manipulation
 */

namespace OptMedia\Utils;

class Filename
{
    /**
     * Change the filename extension
     *
     * @param string $filename The filename
     * @param string $extension The extension to be set
     * @return string The filename with the new extension
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function swapExtension($filename, $extension)
    {
        $pathinfo = pathinfo($filename);

        return "{$pathinfo["dirname"]}/{$pathinfo["filename"]}.{$extension}";
    }

    /**
     * Adds the media sizes into the filename
     *  e.g. An image image.jpg with a 400px width and 300px height
     *  would be: image-400x300.jpg
     *
     * @param string $filename The media filename
     * @param string $width The media width in pixels
     * @param string $height The media height in pixels
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function addSizes($filename, $width, $height)
    {
        $pathinfo = pathinfo($filename);

        return "{$pathinfo["dirname"]}/{$pathinfo["filename"]}-{$width}x{$height}.{$pathinfo["extension"]}";
    }

    /**
     * Check the media file output options and returns the
     * file output path to be used
     *
     * @param string $file The source file
     * @param string $outputFile The output file
     * @param string $outputDir The output directory
     * @return string The file output path to be used
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function getOutput($file, $outputFile, $outputDir)
    {
        $dirname = pathinfo($file, PATHINFO_DIRNAME);

        return $outputDir ? str_replace($dirname, $outputDir, $outputFile) : $outputFile;
    }
}
