<?php

/**
 * Class ServerImageOptimizer
 *
 * Implements the Image Optimization Provider interface
 * with server resources
 */

namespace OptMedia\Providers\Server;

use OptMedia\Lib\Optimizer;
use OptMedia\Utils\Filename;
use OptMedia\Providers\Resources\ImageOptimizer;

class ServerImageOptimizer implements ImageOptimizer
{
    private $file;
    private $optimizerChain;

    public function __construct($file)
    {
        $this->file = $file;
        $this->optimizerChain = Optimizer::getChain();
    }

    public function optimize($outputDir = "")
    {
        $output = Filename::getOutput($this->file, $this->file, $outputDir);

        $this->optimizerChain->optimize($this->file, $output);

        return $output;
    }
}
