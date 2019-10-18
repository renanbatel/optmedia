<?php

namespace OptMedia\Tests\Unit\OptMedia\Utils;

use WP_UnitTestCase;

use OptMedia\Utils\ServerDiagnostic;

class ServerDiagnosticTest extends WP_UnitTestCase
{
    protected $pluginDiagnostic;

    public function setUp(): void
    {
        $this->pluginDiagnostic = ServerDiagnostic::checkPluginRequirements();
    }
    
    /**
     * @test
     * @group utils
     */
    public function checkPluginRequirementsReturnsArray(): void
    {
        $this->assertInternalType("array", $this->pluginDiagnostic);
    }

    /**
     * @test
     * @group utils
     */
    public function checkPluginRequirementsReturnsNotEmptyArray(): void
    {
        $this->assertNotEmpty($this->pluginDiagnostic);
    }
}
