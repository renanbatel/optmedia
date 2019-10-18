<?php

/**
 * Class ServerImageInfo
 *
 * Implements the Image Information Provider interface
 * with server resources
 */

namespace OptMedia\Providers\Server;

use OptMedia\Providers\Resources\ImageInfo;
use OptMedia\Lib\Image;

class ServerImageInfo implements ImageInfo
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

    public function getSizes()
    {
        $image = $this->getImageInstance();

        return [
            "w" => $image->width(),
            "h" => $image->height(),
        ];
    }

    public function getFileSizeInBytes()
    {
        $image = $this->getImageInstance();

        return $image->filesize();
    }
}
