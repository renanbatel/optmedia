<?php

/**
 * Class ImageFactory
 *
 */

namespace OptMedia\Providers\Resources;

use OptMedia\Providers\Resources\Image;
use OptMedia\Providers\Resources\ImageInfo;
use OptMedia\Providers\Resources\ImageManipulator;
use OptMedia\Providers\Resources\ImageOptimizer;

class ImageFactory
{

    protected $imageClass;
    protected $imageInfoClass;
    protected $imageManipulatorClass;
    protected $imageOptimizerClass;

    public function __construct(
        string $imageClass,
        string $imageInfoClass,
        string $imageManipulatorClass,
        string $imageOptimizerClass
    ) {
        $this->imageClass = $imageClass;
        $this->imageInfoClass = $imageInfoClass;
        $this->imageManipulatorClass = $imageManipulatorClass;
        $this->imageOptimizerClass = $imageOptimizerClass;
    }

    /**
     * Creates an Image provider instance
     *
     * @param string $file The image file path
     * @return Image
     *
     * @since 0.1.1
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function getImage(string $file): Image
    {
        return new $this->imageClass($file);
    }

    /**
     * Creates an ImageInfo provider instance
     *
     * @param string $file The image file path
     * @return ImageInfo
     *
     * @since 0.1.1
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function getImageInfo(string $file): ImageInfo
    {
        return new $this->imageInfoClass($file);
    }

    /**
     * Creates an ImageManipulator provider instance
     *
     * @param string $file The image file path
     * @return ImageManipulator
     *
     * @since 0.1.1
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function getImageManipulator(string $file): ImageManipulator
    {
        return new $this->imageManipulatorClass($file);
    }

    /**
     * Creates an ImageOptimizer provider instance
     *
     * @param string $file The image file path
     * @return ImageOptimizer
     *
     * @since 0.1.1
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function getImageOptimizer(string $file): ImageOptimizer
    {
        return new $this->imageOptimizerClass($file);
    }
}
