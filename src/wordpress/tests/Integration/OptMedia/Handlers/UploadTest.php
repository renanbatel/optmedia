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

    protected function assertFiles($files, $sizes)
    {
        foreach ($files as $file) {
            $this->assertTrue(isset($file["sizes"]));
            $this->assertTrue(is_array($file["sizes"]));

            foreach ($sizes as $size) {
                $this->assertTrue(isset($file["sizes"][$size["name"]]));
                $this->assertTrue(isset($file["sizes"][$size["name"]]["file"]));
                $this->assertTrue(isset($file["sizes"][$size["name"]]["url"]));
                $this->assertTrue(isset($file["sizes"][$size["name"]]["fileSize"]));
                $this->assertTrue(isset($file["sizes"][$size["name"]]["width"]));
                $this->assertTrue(isset($file["sizes"][$size["name"]]["height"]));
                $this->assertFileExists($file["sizes"][$size["name"]]["file"]);
            }
        }
    }

    public function setUp(): void
    {
        $tmpDir = get_temp_dir();
        $this->option = new Option();
        $this->mediaSettings = new MediaSettings();
        $this->resourcesImgBasename = dirname(OPTMEDIA_PLUGIN_FILE) . "/tests/Resources/Static/img";
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

        // Sets plugin_imageFormats option to default to generate all the image formats
        $this->option->updateOption(
            Constants::PLUGIN_IMAGE_FORMATS,
            UploadHandler::$defaultImageFormats
        );

        $this->uploadHandler = new UploadHandler(new ImageFactory(
            ServerImage::class,
            ServerImageInfo::class,
            ServerImageManipulator::class,
            ServerImageOptimizer::class
        ));

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

        unlink($this->testImageTarget);
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

        $sizes = $this->mediaSettings->getImageSizes();
        $metaKey = Constants::ATTACHMENT_META_FILES;
        $overrides = [
            "action" => "test",
            "test_form" => false,
        ];
        $file = wp_handle_upload($_FILES["test"], $overrides);
        $attachmentId = $this->uploadHandler->createFileAttachment($file["file"], "png");
        $files = get_post_meta($attachmentId, $metaKey, true);

        $this->assertTrue(is_array($files));
        $this->assertTrue(isset($files["self"]));
        $this->assertTrue(isset($files["jpeg"]));
        $this->assertTrue(isset($files["webp"]));
        $this->assertTrue(isset($files["jpeg"]["id"]));
        $this->assertTrue(isset($files["webp"]["id"]));

        $jpegFiles = get_post_meta($files["jpeg"]["id"], $metaKey, true);
        $webpFiles = get_post_meta($files["webp"]["id"], $metaKey, true);

        $this->assertTrue(is_array($jpegFiles));
        $this->assertTrue(isset($jpegFiles["self"]));
        $this->assertTrue(isset($jpegFiles["png"]));
        $this->assertTrue(isset($jpegFiles["webp"]));
        $this->assertTrue(is_array($webpFiles));
        $this->assertTrue(isset($webpFiles["self"]));
        $this->assertTrue(isset($webpFiles["png"]));
        $this->assertTrue(isset($webpFiles["jpeg"]));

        // To check if the original image information was saved
        $sizes[] = [ "name" => "original" ];

        $this->assertFiles($files, $sizes);
        $this->assertFiles($jpegFiles, $sizes);
        $this->assertFiles($webpFiles, $sizes);

        // Sets plugin_isSetUp option to false
        $this->option->updateOption(Constants::PLUGIN_IS_SETUP, false);
    }
}
