<?php

namespace OptMedia\Tests\Integration\OptMedia\Handlers;

use WP_UnitTestCase;

use OptMedia\Constants;
use OptMedia\Utils\MediaSettings;
use OptMedia\Settings\Option;
use OptMedia\OptMedia;
use OptMedia\Handlers\Upload as UploadHandler;

// Image providers
// TODO: move this to a better place
use OptMedia\Providers\Resources\ImageFactory;
use OptMedia\Providers\Server\ServerImage;
use OptMedia\Providers\Server\ServerImageInfo;
use OptMedia\Providers\Server\ServerImageManipulator;
use OptMedia\Providers\Server\ServerImageOptimizer;

class UploadTest extends WP_UnitTestCase
{
    protected $option;
    protected $mediaSettings;
    protected $uploadHandler;
    protected $uploadDir;
    protected $testImageFilename;
    protected $resourcesImgBasename;
    protected $testImageSource;
    protected $testImageTarget;
    protected $testImageTmp;
    protected $upload;

    protected function assertFormats($formats, $sizes)
    {
        foreach ($formats as $format) {
            $this->assertTrue(isset($format["sizes"]));
            $this->assertTrue(is_array($format["sizes"]));

            foreach ($sizes as $size) {
                $this->assertTrue(isset($format["sizes"][$size["name"]]));
                $this->assertTrue(isset($format["sizes"][$size["name"]]["file"]));
                $this->assertTrue(isset($format["sizes"][$size["name"]]["url"]));
                $this->assertTrue(isset($format["sizes"][$size["name"]]["fileSize"]));
                $this->assertTrue(isset($format["sizes"][$size["name"]]["width"]));
                $this->assertTrue(isset($format["sizes"][$size["name"]]["height"]));
                $this->assertFileExists($format["sizes"][$size["name"]]["file"]);
            }
        }
    }

    public function setUp(): void
    {
        $tmpDir = get_temp_dir();
        $this->option = new Option();
        $this->mediaSettings = new MediaSettings();
        $this->resourcesImgBasename = dirname(OPTMEDIA_PLUGIN_FILE) . "/tests/Resources/Static/img";
        $this->uploadHandler = new UploadHandler(new ImageFactory(
            ServerImage::class,
            ServerImageInfo::class,
            ServerImageManipulator::class,
            ServerImageOptimizer::class
        ));
        $this->uploadDir = wp_upload_dir();
        $this->testImageFilename = "landscape.png";
        $this->testImageSource = "{$this->resourcesImgBasename}/{$this->testImageFilename}";
        $this->testImageTarget = "{$this->uploadDir["path"]}/{$this->testImageFilename}";
        $this->testImageTmp = "{$tmpDir}/{$this->testImageFilename}";
        $this->upload = [
            "file" => $this->testImageTarget,
            "url"  => "{$this->uploadDir["url"]}/{$this->testImageFilename}",
            "type" => "image/png",
        ];
        $_FILES["test"] = [
            "name" => $this->testImageFilename,
            "type" => "image/png",
            "tmp_name" => $this->testImageTmp,
            "error" => 0,
            "size" => filesize($this->testImageSource),
        ];
    }

    public function tearDown(): void
    {
        $_FILES = [];
    }

    /**
     * @test
     * @group int-handler-upload
     */
    public function handleUploadReturnsUnchanged(): void
    {
        copy($this->testImageSource, $this->testImageTarget);

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
        // Sets plugin_isSetUp option to true and reloads the plugin
        // to activate the upload handlers
        $this->option->updateOption(Constants::PLUGIN_IS_SETUP, true);
        (new OptMedia)->run();

        // Copies the source image to the tmp folder
        copy($this->testImageSource, $this->testImageTmp);

        $sizes = $this->mediaSettings->getSizes();
        $metaKey = Constants::ATTACHMENT_META_FORMATS;
        $overrides = [
            "action" => "test",
            "test_form" => false,
        ];
        $file = wp_handle_upload($_FILES["test"], $overrides);
        $attachmentId = $this->uploadHandler->createFileAttachment($file["file"], "png");
        $formats = get_post_meta($attachmentId, $metaKey, true);

        $this->assertTrue(is_array($formats));
        $this->assertTrue(isset($formats["jpeg"]));
        $this->assertTrue(isset($formats["webp"]));
        $this->assertTrue(isset($formats["jpeg"]["id"]));
        $this->assertTrue(isset($formats["webp"]["id"]));

        $jpegFormats = get_post_meta($formats["jpeg"]["id"], $metaKey, true);
        $webpFormats = get_post_meta($formats["webp"]["id"], $metaKey, true);

        $this->assertTrue(is_array($jpegFormats));
        $this->assertTrue(isset($jpegFormats["png"]));
        $this->assertTrue(isset($jpegFormats["webp"]));
        $this->assertTrue(is_array($webpFormats));
        $this->assertTrue(isset($webpFormats["png"]));
        $this->assertTrue(isset($webpFormats["jpeg"]));

        // To check if the original image information was saved
        $sizes[] = [ "name" => "original" ];

        $this->assertFormats($formats, $sizes);
        $this->assertFormats($jpegFormats, $sizes);
        $this->assertFormats($webpFormats, $sizes);

        // Sets plugin_isSetUp option to false
        $this->option->updateOption(Constants::PLUGIN_IS_SETUP, false);
    }
}
