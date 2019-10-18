<?php

namespace OptMedia\Tests\Unit\OptMedia\Handlers;

use WP_UnitTestCase;

use OptMedia\Handlers\Upload as UploadHandler;

class UploadTest extends WP_UnitTestCase
{
    protected $uploadHandler;
    protected $uploadDir;
    protected $testImageFilename;
    protected $resourcesBasename;
    protected $testImageSource;
    protected $testImageTarget;
    protected $upload;

    public function setUp(): void
    {
        $this->resourcesBasename = dirname(OPTMEDIA_PLUGIN_FILE) . "/tests/resources";
        $this->uploadHandler = new UploadHandler();
        $this->uploadDir = wp_upload_dir();
        $this->testImageFilename = "bitcoin.png";
        $this->testImageSource = "{$this->resourcesBasename}/{$this->testImageFilename}";
        $this->testImageTarget = "{$this->uploadDir["path"]}/{$this->testImageFilename}";
        $this->upload = [
            "file" => $this->testImageTarget,
            "url"  => "{$this->uploadDir["url"]}/{$this->testImageFilename}",
            "type" => "image/png",
        ];

        copy($this->testImageSource, $this->testImageTarget);
    }

    public function tearDown(): void
    {
        unlink($this->testImageTarget);
    }
    
    /**
     * @test
     * @group handler-upload
     */
    public function typesAreAllowed(): void
    {
        $allow = [
            "image/jpeg",
            "image/png",
            "image/webp",
            "video/mp4",
            "video/webm",
        ];
        $disallow = [
            "image/gif",
            "image/x-icon",
            "application/pdf",
            "application/msword",
            "application/vnd.ms-powerpoint",
            "application/vnd.oasis.opendocument.text",
            "application/vnd.ms-excel",
            "audio/ogg",
            "audio/x-wav",
            "video/ogg",
            "video/mpeg",
            "video/3gpp",
            "video/3gpp2",
            "video/x-msvideo",
            "video/x-ms-wmv",
            "video/quicktime",
        ];

        foreach ($allow as $allowed) {
            $this->assertTrue($this->uploadHandler->isAllowed($allowed));
        }
        foreach ($disallow as $disallowed) {
            $this->assertFalse($this->uploadHandler->isAllowed($disallowed));
        }
    }

    /**
     * @test
     * @group handler-upload
     */
    public function sameTypesAreVerified(): void
    {
        $this->assertTrue($this->uploadHandler->isSameFormat("image/jpeg", "jpeg"));
        $this->assertFalse($this->uploadHandler->isSameFormat("image/webp", "png"));
    }

    /**
     * @test
     * @group handler-upload
     */
    public function handleUploadReturnsUnchanged(): void
    {
        $this->assertEquals(
            $this->upload,
            $this->uploadHandler->handleUpload($this->upload, "test")
        );
    }

    /**
     * @test
     * @group handler-upload
     */
    public function canHandleImageUpload():void
    {
        $convertedAttachmentsIds = $this->uploadHandler->handleImage($this->upload, "test");

        $this->assertTrue(is_array($convertedAttachmentsIds));
        
        // TODO: test image conversions
        // TODO: test image generated sizes
        foreach ($convertedAttachmentsIds as $convertedAttachmentId) {
            $this->assertTrue(wp_attachment_is_image($convertedAttachmentId));
        }
    }
}
