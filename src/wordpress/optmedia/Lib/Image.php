<?php

/**
 * Class Image
 *
 * This class is a factory of Intervention Image instances
 */

namespace OptMedia\Lib;

use Intervention\Image\ImageManager;

use OptMedia\Utils\ServerDiagnostic;

class Image
{
    /**
     * Check which php image driver is available
     *
     * @return string The available image driver
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    protected static function getAvailableDriver()
    {
        $serverDiagnostic = new ServerDiagnostic();
        $candidates = [
            [
                "name" => "gd",
                "type" => "extension",
            ],
            [
                "name" => "imagick",
                "type" => "extension",
            ],
        ];
        $diagnostic = $serverDiagnostic->checkRequirements($candidates);

        if ($diagnostic["imagick"]["passed"]) {
            return "imagick";
        } else if ($diagnostic["gd"]["passed"]) {
            return "gd";
        }

        // Return gd by default
        return "gd";
    }

    /**
     * Gets the ImageManager instance
     *
     * @return \Intervention\Image\ImageManager The ImageManager instance
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function getManager()
    {
        return new ImageManager([
            "driver" => self::getAvailableDriver(),
        ]);
    }

    /**
     * Gets the Image instance
     *
     * @param string $filename The image file path
     * @return \Intervention\Image\Image The Image instance
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function getInstance($filename)
    {
        $manager = self::getManager();

        return $manager->make($filename);
    }
}
