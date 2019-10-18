<?php

/**
 * Class ServerDiagnostic
 *
 * This class is used to check if the server attends to the plugin
 * requirements (to the server provider utilization)
 */

namespace OptMedia\Utils;

use OptMedia\Helpers;

class ServerDiagnostic
{
    
    /**
     * Check if server has support for an specific requirement
     *
     * @param array $requirement
     * @return array The requirement with check results
     *
     * @since 0.1.0
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public static function checkRequirement($requirement)
    {
        $result = [
            "name" => $requirement[ "name" ],
            "type" => $requirement[ "type" ],
        ];

        if (isset($requirement[ "equivalent" ])) {
            $result[ "equivalent" ] = $requirement[ "equivalent" ];
        }

        if ($requirement[ "type" ] === "extension") {
            $result[ "passed" ] = extension_loaded($requirement[ "name" ]);
        } else if ($requirement[ "type" ] === "command") {
            $result[ "passed" ] = Helpers\Misc::commandExists($requirement[ "name" ]);
        }

        // If extension didn't load, return it
        if (isset($result[ "passed" ]) && !$result[ "passed" ]) {
            return $result;
        }

        if (isset($requirement[ "version" ])) {
            $result[ "required" ] = $requirement[ "version" ];

            if ($requirement[ "type" ] === "php") {
                $result[ "version" ] = PHP_VERSION;
            } else if ($requirement[ "type" ] === "extension") {
                $result[ "version" ] = phpversion($requirement[ "name" ]);
            }

            $result[ "passed" ] = version_compare($result[ "version" ], $result[ "required" ], ">=");
        }

        return $result;
    }

    /**
     * Check a list of requirements
     *
     * @param array $requirements
     * @return array The diagnostic results
     *
     * @since 0.1.0
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public static function checkRequirements($requirements)
    {
        $diagnostic = array_map([ new self, "checkRequirement" ], $requirements);
        $results = array_reduce($diagnostic, function ($carry, $current) {
            $name = $current["name"];
            $carry[$name] = $current;

            unset($carry[$name]["name"]);

            return $carry;
        }, []);

        return $results;
    }

    /**
     * Check if server has plugin required modules
     *
     * @return array The diagnostic results
     *
     * @since 0.1.0
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public static function checkPluginRequirements()
    {
        $requirements = [
            [
                "name"    => "php",
                "type"    => "php",
                "version" => "7.0",
            ],
            [
                "name" => "fileinfo",
                "type" => "extension",
            ],
            [
                "name"       => "gd",
                "type"       => "extension",
                "version"    => "2.0",
                "equivalent" => "imagick",
            ],
            [
                "name"       => "imagick",
                "type"       => "extension",
                "version"    => "6.5.7",
                "equivalent" => "gd",
            ],
            [
                "name" => "optipng",
                "type" => "command",
            ],
            [
                "name" => "jpegoptim",
                "type" => "command",
            ],
            [
                "name" => "cwebp",
                "type" => "command",
            ],
            [
                "name" => "ffmpeg",
                "type" => "command",
            ],
            [
                "name" => "ffprobe",
                "type" => "command",
            ],
        ];

        return self::checkRequirements($requirements);
    }
}
