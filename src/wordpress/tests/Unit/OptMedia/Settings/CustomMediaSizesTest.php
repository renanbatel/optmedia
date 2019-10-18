<?php

namespace OptMedia\Tests\Unit\OptMedia\Settings;

use WP_UnitTestCase;

use OptMedia\Settings\CustomMediaSizes;
use OptMedia\Utils\MediaSettings;

class CustomMediaSizesTest extends WP_UnitTestCase
{

    protected $customMediaSizes;
    protected $mediaSettings;
    protected $customSizes;

    public function setUp(): void
    {
        $this->customMediaSizes = new CustomMediaSizes();
        $this->mediaSettings = new MediaSettings();
        $this->customSizes = [
            [
                "name"   => "custom_size_1",
                "width"  => 777,
                "height" => 777,
                "crop"   => false,
            ],
            [
                "name"   => "custom_size_2",
                "width"  => 999,
                "height" => 999,
                "crop"   => true,
            ],
        ];
    }

  /**
     * @test
     * @group settings
     */
    public function canUpdate(): void
    {
        $success = $this->customMediaSizes->update($this->customSizes);

        $this->assertTrue($success);
    }

  /**
     * @test
     * @group settings
     */
    public function canGet(): void
    {
        $customSizes = $this->customMediaSizes->get();

        $this->assertEquals($customSizes, $this->customSizes);
    }

  /**
     * @test
     * @group settings
     */
    public function canSet(): void
    {
        // Reload settings
        $this->customMediaSizes->load();
        
        $mediaSizes = $this->mediaSettings->getSizes();

        foreach ($this->customSizes as $key => $customSize) {
            $this->assertContains($customSize, $mediaSizes);
        }
    }
}
