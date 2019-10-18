<?php

namespace OptMedia\Tests\Unit\OptMedia\Providers\Server\Resources;

use WP_UnitTestCase;

use OptMedia\Utils\Directory;

abstract class ImageTestCase extends WP_UnitTestCase
{
    protected $basedir;
    protected $tmpDir;
    protected $webpFile;
    protected $jpgFile;
    protected $pngFile;

    public function setUp(): void
    {
        $this->basedir = dirname(OPTMEDIA_PLUGIN_FILE) . "/tests/resources";
        $this->tmpDir = "{$this->basedir}/.tmp";
        $this->webpFile = "{$this->basedir}/waterski.webp";
        $this->jpgFile = "{$this->basedir}/tiger.jpg";
        $this->pngFile = "{$this->basedir}/bitcoin.png";
        
        mkdir($this->tmpDir);
    }
    
    public function tearDown(): void
    {
        Directory::rrmdir($this->tmpDir);
    }
}
