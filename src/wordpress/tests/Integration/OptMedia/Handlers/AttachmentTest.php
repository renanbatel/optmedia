<?php

namespace OptMedia\Tests\Integration\OptMedia\Handlers;

use DOMDocument;
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
        $this->attachment = new Handlers\Attachment();
    }

    /**
     * @test
     * @group int-handler-attachment
     */
    public function canHandleImageSrcsetCalculation()
    {
        $uploadDir = wp_upload_dir();
        $resourcesImgBasename = dirname(OPTMEDIA_PLUGIN_FILE) . "/tests/Resources/Static/img";
        $upload = new Handlers\Upload(
            $this->getMockBuilder(ImageFactory::class)
                ->disableOriginalConstructor()
                ->getMock()
        );
        $testImageSource = "{$resourcesImgBasename}/landscape.png";
        $testImageTarget = "{$uploadDir["path"]}/landscape.png";

        copy($testImageSource, $testImageTarget);
        
        $attachmentId = $upload->createFileAttachment($testImageTarget, "png");
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

        unlink($testImageTarget);
    }

    /**
     * @test
     * @group int-handler-attachment
     */
    public function canHandlePostContentFiltering()
    {
        $srcContent = "
            <div>
                <img 
                    src=\"img.jpg\"
                    class=\"img-class\"
                />
            </div>
        ";
        $srcsetContent = "
            <div>
                <img
                    alt=\"Img\"
                    srcset=\"img-300.jpg 300w, img-768.jpg 768w\"
                    sizes=\"300w, 768w\"
                />
            </div>
        ";
        $bothContent = "
            <div>
                <img
                    alt=\"Img\"
                    src=\"img.jpg\"
                    srcset=\"img-300.jpg 300w, img-768.jpg 768w\"
                    sizes=\"300w, 768w\"
                    class=\"img-both\"
                />
            </div>
        ";
        $srcExpected = [
            "data-src" => "img.jpg",
            "class" => "img-class om-lazy-load",
        ];
        $srcsetExpected = [
            "alt" => "Img",
            "data-srcset" => "img-300.jpg 300w, img-768.jpg 768w",
            "data-sizes" => "300w, 768w",
            "class" => "om-lazy-load",
        ];
        $bothExpected = [
            "alt" => "Img",
            "data-src" => "img.jpg",
            "data-srcset" => "img-300.jpg 300w, img-768.jpg 768w",
            "data-sizes" => "300w, 768w",
            "class" => "img-both om-lazy-load",
        ];
        
        $srcFiltered =  $this->attachment->handlePostContent($srcContent);
        $srcsetFiltered =  $this->attachment->handlePostContent($srcsetContent);
        $bothFiltered =  $this->attachment->handlePostContent($bothContent);

        $this->assertFalse(strpos($srcFiltered, "<body>"));
        $this->assertFalse(strpos($srcFiltered, "</body>"));
        $this->assertFalse(strpos($srcsetFiltered, "<body>"));
        $this->assertFalse(strpos($srcsetFiltered, "</body>"));
        $this->assertFalse(strpos($bothFiltered, "<body>"));
        $this->assertFalse(strpos($bothFiltered, "</body>"));

        libxml_use_internal_errors(true);

        $srcHTML = new DOMDocument();
        $srcsetHTML = new DOMDocument();
        $bothHTML = new DOMDocument();

        $srcHTML->loadHTML($srcFiltered);
        $srcsetHTML->loadHTML($srcsetFiltered);
        $bothHTML->loadHTML($bothFiltered);

        $srcImage = $srcHTML->getElementsByTagName("img")[0];
        $srcsetImage = $srcsetHTML->getElementsByTagName("img")[0];
        $bothImage = $bothHTML->getElementsByTagName("img")[0];

        $this->assertFalse($srcImage->hasAttribute("src"));

        foreach ($srcExpected as $key => $value) {
            $this->assertEquals($value, $srcImage->getAttribute($key));
        }

        $this->assertFalse($srcsetImage->hasAttribute("srcset"));
        $this->assertFalse($srcsetImage->hasAttribute("sizes"));

        foreach ($srcsetExpected as $key => $value) {
            $this->assertEquals($value, $srcsetImage->getAttribute($key));
        }

        $this->assertFalse($bothImage->hasAttribute("src"));
        $this->assertFalse($bothImage->hasAttribute("srcset"));
        $this->assertFalse($bothImage->hasAttribute("sizes"));

        foreach ($bothExpected as $key => $value) {
            $this->assertEquals($value, $bothImage->getAttribute($key));
        }

        libxml_use_internal_errors(false);
    }
}
