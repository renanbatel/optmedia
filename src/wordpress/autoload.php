<?php

/**
 * Registers an PSR-4 autoloader for the plugin classes
 * Based on: https://www.php-fig.org/psr/psr-4/examples/#closure-example
 */

spl_autoload_register(function ($class) {
    $prefixes = [
        [
            "namespace" => "OptMedia\\Tests\\",
            "basedir" => __DIR__ . "/tests/",
        ],
        [
            "namespace" => "OptMedia\\",
            "basedir" => __DIR__ . "/optmedia/",
        ],
    ];

    foreach ($prefixes as $prefix) {
        $length = strlen($prefix["namespace"]);
    
        if (strncmp($prefix["namespace"], $class, $length) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $length);
        $slashed = str_replace("\\", "/", $relativeClass);
        $file = "{$prefix["basedir"]}{$slashed}.php";

        if (file_exists($file)) {
            require $file;
        }
    }
});
