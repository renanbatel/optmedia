<?php

namespace OptMedia\Tests\Unit\OptMedia\Providers\Server;

use OptMedia\Tests\Unit\OptMedia\Providers\Server\Resources\ImageTestCase;
use OptMedia\Providers\Server\ServerImageManipulator;

class ServerImageManipulatorTest extends ImageTestCase
{
    protected $webpImageManipulator;
    protected $jpgImageManipulator;
    protected $pngImageManipulator;

    public function setUp(): void
    {
        parent::setUp();

        $this->webpImageManipulator = new ServerImageManipulator($this->webpFile);
        $this->jpgImageManipulator = new ServerImageManipulator($this->jpgFile);
        $this->pngImageManipulator = new ServerImageManipulator($this->pngFile);
    }

    /**
     * @test
     * @group server-image-manipulator
     */
    public function canConvert(): void
    {
        $webpToPngConvertedFile = "{$this->tmpDir}/waterski.png";
        $webpToJpgConvertedFile = "{$this->tmpDir}/waterski.jpg";
        $jpgToPngConvertedFile = "{$this->tmpDir}/tiger.png";
        $jpgToWebpConvertedFile = "{$this->tmpDir}/tiger.webp";
        $pngToWebpConvertedFile = "{$this->tmpDir}/bitcoin.webp";
        $pngToJpgConvertedFile = "{$this->tmpDir}/bitcoin.jpg";
        
        $this->assertFileEquals(
            $webpToPngConvertedFile,
            $this->webpImageManipulator->convert("png", $this->tmpDir)
        );
        $this->assertFileEquals(
            $webpToJpgConvertedFile,
            $this->webpImageManipulator->convert("jpg", $this->tmpDir)
        );
        $this->assertFileEquals(
            $jpgToPngConvertedFile,
            $this->jpgImageManipulator->convert("png", $this->tmpDir)
        );
        $this->assertFileEquals(
            $jpgToWebpConvertedFile,
            $this->jpgImageManipulator->convert("webp", $this->tmpDir)
        );
        $this->assertFileEquals(
            $pngToWebpConvertedFile,
            $this->pngImageManipulator->convert("webp", $this->tmpDir)
        );
        $this->assertFileEquals(
            $pngToJpgConvertedFile,
            $this->pngImageManipulator->convert("jpg", $this->tmpDir)
        );
        $this->assertFileExists($webpToPngConvertedFile);
        $this->assertFileExists($webpToJpgConvertedFile);
        $this->assertFileExists($jpgToPngConvertedFile);
        $this->assertFileExists($jpgToWebpConvertedFile);
        $this->assertFileExists($pngToWebpConvertedFile);
        $this->assertFileExists($pngToJpgConvertedFile);
    }

    /**
     * @test
     * @group server-image-manipulator
     */
    public function canResize(): void
    {
        $webpResizedFile = "{$this->tmpDir}/waterski-680x453.webp";
        $noWebpResizedFile = "{$this->tmpDir}/waterski-1480x986.webp";
        $jpgResizedFile = "{$this->tmpDir}/tiger-720x600.jpg";
        $noJpgResizedFile = "{$this->tmpDir}/tiger-10000x10000.jpg";
        $pngResizedFile = "{$this->tmpDir}/bitcoin-650x333.png";
        $noPngResizedFile = "{$this->tmpDir}/bitcoin-1200x1010.png";
        
        $this->assertFileEquals(
            $webpResizedFile,
            $this->webpImageManipulator->resize(680, null, false, $this->tmpDir)
        );
        $this->assertFileEquals(
            $jpgResizedFile,
            $this->jpgImageManipulator->resize(720, 600, true, $this->tmpDir)
        );
        $this->assertFileEquals(
            $pngResizedFile,
            $this->pngImageManipulator->resize(650, 333, true, $this->tmpDir)
        );
        $this->assertNull(
            $this->webpImageManipulator->resize(1480, null, false, $this->tmpDir)
        );
        $this->assertNull(
            $this->jpgImageManipulator->resize(10000, 10000, true, $this->tmpDir)
        );
        $this->assertNull(
            $this->pngImageManipulator->resize(1200, 1010, true, $this->tmpDir)
        );
        $this->assertFileExists($webpResizedFile);
        $this->assertFileExists($jpgResizedFile);
        $this->assertFileExists($pngResizedFile);
        $this->assertFalse(file_exists($noWebpResizedFile));
        $this->assertFalse(file_exists($noJpgResizedFile));
        $this->assertFalse(file_exists($noPngResizedFile));
        // TODO: test generated image dimensions with ImageInfo
    }
}
