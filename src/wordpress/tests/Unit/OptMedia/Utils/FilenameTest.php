<?php

namespace OptMedia\Tests\Unit\OptMedia\Utils;

use WP_UnitTestCase;

use OptMedia\Utils\Filename;

class FilenameTest extends WP_UnitTestCase
{

    /**
     * @test
     * @group unit-utils
     */
    public function canSwapExtension(): void
    {
        $filename = "/fake/path/file.abc";
        $extension = "efg";
        $expected = "/fake/path/file.{$extension}";

        $this->assertEquals($expected, Filename::swapExtension($filename, $extension));
    }

    /**
     * @test
     * @group unit-utils
     */
    public function canAddSizes(): void
    {
        $filename = "/fake/path/file.abc";
        $width = 357;
        $height = 753;
        $expected = "/fake/path/file-357x753.abc";

        $this->assertEquals($expected, Filename::addSizes($filename, $width, $height));
    }
}
