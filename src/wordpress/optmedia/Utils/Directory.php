<?php

/**
 * Class Directory
 *
 * This class has some directory utilities for the plugin usage
 */

namespace OptMedia\Utils;

class Directory
{
    /**
     * Recursively remove an directory
     *
     * @param string $dirPath The path for the directory to be excluded
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function rrmdir($dirPath)
    {
        if (is_dir($dirPath)) {
            $objects = scandir($dirPath);

            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir("{$dirPath}/{$object}")) {
                        rrmdir("{$dirPath}/{$object}");
                    } else {
                        unlink("{$dirPath}/{$object}");
                    }
                }
            }
            rmdir($dirPath);
        }
    }
}
