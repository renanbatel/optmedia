<?php

namespace OptMedia\Tests\Unit\OptMedia\Handlers;

use WP_UnitTestCase;

use OptMedia\Handlers\Upload as UploadHandler;

class UploadTest extends WP_UnitTestCase
{
    protected $uploadHandler;

    public function setUp()
    {
        $this->uploadHandler = new UploadHandler();
    }
    
    /**
     * @test
     * @group unit-handler-upload
     */
    public function typesAreAllowed()
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
     * @group unit-handler-upload
     */
    public function sameTypesAreVerified()
    {
        $this->assertTrue($this->uploadHandler->isSameFormat("image/jpeg", "jpeg"));
        $this->assertFalse($this->uploadHandler->isSameFormat("image/webp", "png"));
    }
}
