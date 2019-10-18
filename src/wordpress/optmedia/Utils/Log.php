<?php

/**
 * Class Log
 *
 * This class offers a way to output some data to a file
 * this can be used to log data from methods used on ajax requests
 *
 * ! This class must be used only in development
 */

namespace OptMedia\Utils;

class Log
{

    /**
     * Gets the log file path
     *
     * @return string The log file path
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    protected static function getFilePath()
    {
        return WP_CONTENT_DIR . "/logs/plugin.log";
    }

    /**
     * Prepare data for logging
     *
     * @param mixed $content The data to be logged
     * @return string The prepared string for log
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    protected static function getData($content): string
    {
        $date = date("c");
        $normalized = print_r($content, true);

        return "[{$date}]    {$normalized}\n";
    }

    /**
     * Add contents to the plugin log file
     *
     * @param mixed ...$contents The contents to be logged
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function add(...$contents)
    {
        foreach ($contents as $content) {
            file_put_contents(self::getFilePath(), self::getData($content), FILE_APPEND);
        }
    }
}
