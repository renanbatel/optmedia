<?php

/**
 * Class Optimizer
 *
 * This class is a factory of OptimizerChain instance
 */

namespace OptMedia\Lib;

use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\Optimizers\Pngquant;
use Spatie\ImageOptimizer\Optimizers\Optipng;
use Spatie\ImageOptimizer\Optimizers\Jpegoptim;
use Spatie\ImageOptimizer\Optimizers\Cwebp;

class Optimizer
{
    /**
     * Gets the OptimizerChain instance
     *
     * @return \Spatie\ImageOptimizer\OptimizerChain The OptimizerChain instance
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function getChain()
    {
        $pngquant = new Pngquant(["--force"]);
        $optipng = new Optipng([
            "-i0",
            "-o2",
        ]);
        $jpegoptim = new Jpegoptim([
            "-m85",
            "--strip-all",
            "--all-progressive",
        ]);
        $cwebp = new Cwebp([
            "-m 6",
            "-pass 10",
            "-mt",
            "-q 80",
        ]);
        
        return (new OptimizerChain)
            ->setTimeout(30)
            ->addOptimizer($pngquant)
            ->addOptimizer($optipng)
            ->addOptimizer($jpegoptim)
            ->addOptimizer($cwebp);
    }
}
