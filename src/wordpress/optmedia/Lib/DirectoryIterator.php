<?php

namespace OptMedia\Lib;

use RegexIterator;
use RecursiveRegexIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class DirectoryIterator
{

    public static function getFiles($rootDir, $extensions = [])
    {
        $extensions    = join("|", ( array ) $extensions);
        $dirIterator   = new RecursiveDirectoryIterator($rootDir, RecursiveDirectoryIterator::SKIP_DOTS);
        $fileIterator  = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::SELF_FIRST);
        $regexIterator = new RegexIterator($fileIterator, "/^.+\.({$extensions})$/i", RecursiveRegexIterator::GET_MATCH);
        $files         = [];
    
        foreach ($regexIterator as $key => $file) {
            if (is_array($file)) {
                $files[] = reset($file);
            } else {
                $files[] = $file;
            }
        }

        return $files;
    }
}
