<?php

namespace OptMedia\Tests\Integration\OptMedia\Handlers;

use WP_UnitTestCase;

use OptMedia\Constants;
use OptMedia\Handlers;
use OptMedia\Providers\Resources\ImageFactory;

class AttachmentTest extends WP_UnitTestCase
{
    protected $upload;
    protected $attachment;
    protected $testImageSource;
    protected $testImageTarget;

    public function setUp()
    {
        $uploadDir = wp_upload_dir();
        $resourcesImgBasename = dirname(OPTMEDIA_PLUGIN_FILE) . "/tests/Resources/Static/img";

        $this->upload = new Handlers\Upload(
            $this->getMockBuilder(ImageFactory::class)
                ->disableOriginalConstructor()
                ->getMock()
        );
        $this->attachment = new Handlers\Attachment();
        $this->testImageSource = "{$resourcesImgBasename}/landscape.png";
        $this->testImageTarget = "{$uploadDir["path"]}/landscape.png";

        copy($this->testImageSource, $this->testImageTarget);
    }

    public function tearDown()
    {
        unlink($this->testImageTarget);
    }

    /**
     * @test
     * @group int-handler-attachment
     */
    public function canHandleImageSrcsetCalculation()
    {
        $attachmentId = $this->upload->createFileAttachment($this->testImageTarget, "png");
        $files = [
            "self" => [
                "sizes" => [
                    "thumbnail" => [
                        "fileSize" => 100,
                        "url" => "self_thumbnail",
                    ],
                    "medium" => [
                        "fileSize" => 300,
                        "url" => "self_medium",
                    ],
                    "large" => [
                        "fileSize" => 200,
                        "url" => "self_large",
                    ],
                ],
            ],
            "webp" => [
                "sizes" => [
                    "thumbnail" => [
                        "fileSize" => 200,
                        "url" => "webp_thumbnail",
                    ],
                    "medium" => [
                        "fileSize" => 100,
                        "url" => "webp_medium",
                    ],
                    "large" => [
                        "fileSize" => 300,
                        "url" => "webp_large",
                    ],
                ],
            ],
            "jpeg" => [
                "sizes" => [
                    "thumbnail" => [
                        "fileSize" => 300,
                        "url" => "jpeg_thumbnail",
                    ],
                    "medium" => [
                        "fileSize" => 200,
                        "url" => "jpeg_medium",
                    ],
                    "large" => [
                        "fileSize" => 100,
                        "url" => "jpeg_large",
                    ],
                ],
            ],
        ];
        $sources = [
            "150" => [
                "url" => "",
                "descriptor" => "w",
                "value" => "150",
            ],
            "300" => [
                "url" => "",
                "descriptor" => "h",
                "value" => "300",
            ],
            "1024" => [
                "url" => "",
                "descriptor" => "h",
                "value" => "1024",
            ],
        ];
        $expected = $sources;
        $expected["150"]["url"] = "self_thumbnail";
        $expected["300"]["url"] = "webp_medium";
        $expected["1024"]["url"] = "jpeg_large";

        update_post_meta($attachmentId, Constants::ATTACHMENT_META_FILES, $files);

        $this->assertEquals(
            $expected,
            $this->attachment->handleImageSrcsetCalculation($sources, [], "", [], $attachmentId)
        );
    }
}
