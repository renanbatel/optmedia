<?php

namespace OptMedia\Tests\Unit\OptMedia\Utils;

use WP_UnitTestCase;

use OptMedia\Utils\MediaSettings;

class MediaSettingsTest extends WP_UnitTestCase
{
    protected $mediaSettings;
    protected $sizes;

    public function setUp(): void
    {
        $this->mediaSettings = new MediaSettings();
        $this->sizes = $this->mediaSettings->getSizes();
    }

    /**
     * @test
     * @group utils
     */
    public function getSizesReturnsArray(): void
    {
        $this->assertInternalType("array", $this->sizes);
    }

    /**
     * @test
     * @group utils
     */
    public function getSizesReturnsDefaultSizes(): void
    {
        $defaultSizes = [
            ["name" => "thumbnail"],
            ["name" => "medium"],
            ["name" => "medium_large"],
            ["name" => "large"],
        ];

        $this->assertArraySubset($defaultSizes, $this->sizes);
    }
}
