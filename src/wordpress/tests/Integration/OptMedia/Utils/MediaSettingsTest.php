<?php

namespace OptMedia\Tests\Integration\OptMedia\Utils;

use WP_UnitTestCase;

use OptMedia\Utils\MediaSettings;

class MediaSettingsTest extends WP_UnitTestCase
{
    protected $mediaSettings;

    public function setUp(): void
    {
        $this->mediaSettings = new MediaSettings();
    }

    /**
     * @test
     * @group int-utils
     */
    public function getSizesReturnsArray(): void
    {
        $sizes = $this->mediaSettings->getImageSizes();

        $this->assertInternalType("array", $sizes);
    }

    /**
     * @test
     * @group int-utils
     */
    public function getSizesReturnsDefaultSizes(): void
    {
        $sizes = $this->mediaSettings->getImageSizes();
        $defaultSizes = [
            ["name" => "thumbnail"],
            ["name" => "medium"],
            ["name" => "medium_large"],
            ["name" => "large"],
        ];

        $this->assertArraySubset($defaultSizes, $sizes);
    }

    /**
     * @test
     * @group int-utils
     */
    public function canGetSizeNameByDimension()
    {
        $mediumLarge = $this->mediaSettings->getImageSizeNameByDimension("w", 768);
        $large = $this->mediaSettings->getImageSizeNameByDimension("h", "1024");
        $original = $this->mediaSettings->getImageSizeNameByDimension("w", "101010");

        $this->assertEquals($mediumLarge, "medium_large");
        $this->assertEquals($large, "large");
        $this->assertEquals($original, "original");
    }
}
