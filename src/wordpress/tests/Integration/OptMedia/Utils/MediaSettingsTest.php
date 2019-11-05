<?php

namespace OptMedia\Tests\Integration\OptMedia\Utils;

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
     * @group int-utils
     */
    public function getSizesReturnsArray(): void
    {
        $this->assertInternalType("array", $this->sizes);
    }

    /**
     * @test
     * @group int-utils
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
