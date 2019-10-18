<?php

/**
 * Class Misc
 *
 * This class some varied helper functions
 */

namespace OptMedia\Helpers;

class Misc
{
    /**
     * Check if command exists on server
     *
     * @param string $command
     * @return boolean True if the command exists, false if it doesn't
     *
     * @since 0.1.0
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public static function commandExists($command)
    {
        $windows = strpos(PHP_OS, "WIN") === 0;
        $test = $windows ? "where" : "command -v";
        
        return is_executable(trim(shell_exec("$test $command")));
    }
}
