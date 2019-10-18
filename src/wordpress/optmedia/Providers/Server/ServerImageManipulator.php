<?php

/**
 * Class ServerImageManipulator
 *
 * Implements the Image Manipulation Provider interface
 * with server resources
 */

namespace OptMedia\Providers\Server;

use OptMedia\Lib\Image;
use OptMedia\Utils\Filename;
use OptMedia\Providers\Resources\ImageManipulator;

class ServerImageManipulator implements ImageManipulator
{
    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function getImageInstance()
    {
        return Image::getInstance($this->file);
    }

    public function canResize($image, $width, $height)
    {
        return ($width && $image->width() >= $width)
            || ($height && $image->height() >= $height);
    }

    public function convert($format, $outputDir = "")
    {
        $image = $this->getImageInstance();
        $outputFile = Filename::swapExtension($this->file, $format);
        $output = Filename::getOutput($this->file, $outputFile, $outputDir);

        // TODO: Make quality value a setting
        $image->save($output, 90);

        return $output;
    }

    public function resize($width, $height, $cut = false, $outputDir = "")
    {
        $image = $this->getImageInstance();
        // Prevent zero values (this would throw a division by zero error)
        $width = intval($width) === 0 ? null : $width;
        $height = intval($height) === 0 ? null : $height;

        if ($this->canResize($image, $width, $height)) {
            if ($cut) {
                $image->fit($width, $height, function ($constraint) {
                    $constraint->upsize();
                });
            } else {
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
    
            $outputFile = Filename::addSizes(
                $this->file,
                $image->width(),
                $image->height()
            );
            $output = Filename::getOutput($this->file, $outputFile, $outputDir);
    
            // TODO: Make quality value a setting
            $image->save($output, 90);
    
            return $output;
        }

        return null;
    }
}
