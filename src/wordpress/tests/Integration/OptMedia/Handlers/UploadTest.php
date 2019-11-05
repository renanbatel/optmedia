<?php

namespace OptMedia\Tests\Integration\OptMedia\Handlers;

use WP_UnitTestCase;

use OptMedia\Handlers\Upload as UploadHandler;

class UploadTest extends WP_UnitTestCase
{
    protected $uploadHandler;
    protected $uploadDir;
    protected $testImageFilename;
    protected $resourcesImgBasename;
    protected $testImageSource;
    protected $testImageTarget;
    protected $upload;

    public function setUp(): void
    {
        $this->resourcesImgBasename = dirname(OPTMEDIA_PLUGIN_FILE) . "/tests/Resources/Static/img";
        $this->uploadHandler = new UploadHandler();
        $this->uploadDir = wp_upload_dir();
        $this->testImageFilename = "bitcoin.png";
        $this->testImageSource = "{$this->resourcesImgBasename}/{$this->testImageFilename}";
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
     * @group int-handler-upload
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
     * @group int-handler-upload
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
