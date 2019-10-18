<?php

namespace OptMedia\Tests\Unit\OptMedia\Providers\Server;

use WP_UnitTestCase;

use OptMedia\Providers\Server\ServerImageInfo;

class ServerImageInfoTest extends WP_UnitTestCase
{
    protected $webpFile;
    protected $jpgFile;
    protected $pngFile;
    protected $webpImageInfo;
    protected $jpgImageInfo;
    protected $pngImageInfo;

    public function setUp(): void
    {
        $this->webpFile = dirname(OPTMEDIA_PLUGIN_FILE) . "/tests/resources/waterski.webp";
        $this->jpgFile = dirname(OPTMEDIA_PLUGIN_FILE) . "/tests/resources/tiger.jpg";
        $this->pngFile = dirname(OPTMEDIA_PLUGIN_FILE) . "/tests/resources/bitcoin.png";
        $this->webpImageInfo = new ServerImageInfo($this->webpFile);
        $this->jpgImageInfo = new ServerImageInfo($this->jpgFile);
        $this->pngImageInfo = new ServerImageInfo($this->pngFile);
    }
    
    /**
     * @test
     * @group server-image-info
     */
    public function canGetSizes(): void
    {
        $webpSizes = [
            "w" => 1280,
            "h" => 853,
        ];
        $jpgSizes = [
            "w" => 6000,
            "h" => 4000,
        ];
        $pngSizes = [
            "w" => 865,
            "h" => 861,
        ];

        $this->assertEquals($webpSizes, $this->webpImageInfo->getSizes());
        $this->assertEquals($jpgSizes, $this->jpgImageInfo->getSizes());
        $this->assertEquals($pngSizes, $this->pngImageInfo->getSizes());
    }

    /**
     * @test
     * @group server-image-info
     */
    public function canFileSizeInBytes(): void
    {
        $webpFileSizeInBytes = 212586;
        $jpgFileSizeInBytes = 2271626;
        $pngFileSizeInBytes = 1598505;

        $this->assertEquals($webpFileSizeInBytes, $this->webpImageInfo->getFileSizeInBytes());
        $this->assertEquals($jpgFileSizeInBytes, $this->jpgImageInfo->getFileSizeInBytes());
        $this->assertEquals($pngFileSizeInBytes, $this->pngImageInfo->getFileSizeInBytes());
    }
}
