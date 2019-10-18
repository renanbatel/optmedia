<?php

/**
 * Class ServerImage
 *
 * Implements the Image Provider interface with all Server Image Providers
 */

namespace OptMedia\Providers\Server;

use OptMedia\Providers\Resources\Image;
use OptMedia\Providers\Server\ServerImageInfo;
use OptMedia\Providers\Server\ServerImageManipulator;
use OptMedia\Providers\Server\ServerImageOptimizer;

class ServerImage implements Image
{
    public $info;
    public $manipulator;
    public $optimizer;

    public function __construct($file)
    {
        $this->info = new ServerImageInfo($file);
        $this->manipulator = new ServerImageManipulator($file);
        $this->optimizer = new ServerImageOptimizer($file);
    }
}
