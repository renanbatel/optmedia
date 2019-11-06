<?php

namespace OptMedia\Tests\Integration\OptMedia\Providers\Server;

use OptMedia\Tests\Resources\ImageTestCase;
use OptMedia\Providers\Server\ServerImageInfo;
use OptMedia\Providers\Server\ServerImageOptimizer;

class ServerImageOptimizerTest extends ImageTestCase
{
    protected $webpImageOptimizer;
    protected $jpgImageOptimizer;
    protected $pngImageOptimizer;

    public function setUp(): void
    {
        parent::setUp();

        $this->webpImageOptimizer = new ServerImageOptimizer($this->webpFile);
        $this->jpgImageOptimizer = new ServerImageOptimizer($this->jpgFile);
        $this->pngImageOptimizer = new ServerImageOptimizer($this->pngFile);
    }

   /**
     * @test
     * @group int-server-image-optimizer
     */
    public function canOptimizeImage(): void
    {
        $webpOptimizedImage = $this->webpImageOptimizer->optimize($this->tmpDir);
        $webpImageInfo = new ServerImageInfo($this->webpFile);
        $webpOptimizedImageInfo = new ServerImageInfo($webpOptimizedImage);
        $jpgOptimizedImage = $this->jpgImageOptimizer->optimize($this->tmpDir);
        $jpgImageInfo = new ServerImageInfo($this->jpgFile);
        $jpgOptimizedImageInfo = new ServerImageInfo($jpgOptimizedImage);
        $pngOptimizedImage = $this->pngImageOptimizer->optimize($this->tmpDir);
        $pngImageInfo = new ServerImageInfo($this->pngFile);
        $pngOptimizedImageInfo = new ServerImageInfo($pngOptimizedImage);

        $this->assertLessThan(
            $webpImageInfo->getFileSizeInBytes(),
            $webpOptimizedImageInfo->getFileSizeInBytes()
        );
        $this->assertLessThan(
            $jpgImageInfo->getFileSizeInBytes(),
            $jpgOptimizedImageInfo->getFileSizeInBytes()
        );
        $this->assertLessThan(
            $pngImageInfo->getFileSizeInBytes(),
            $pngOptimizedImageInfo->getFileSizeInBytes()
        );
    }
}
