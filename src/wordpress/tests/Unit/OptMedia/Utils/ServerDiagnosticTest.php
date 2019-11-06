<?php

namespace OptMedia\Tests\Unit\OptMedia\Utils;

use WP_UnitTestCase;

use OptMedia\Utils\ServerDiagnostic;
use OptMedia\Helpers;

class ServerDiagnosticTest extends WP_UnitTestCase
{
    protected $helperMiscMock;
    protected $serverDiagnostic;

    public function setUp(): void
    {
        $this->helperMiscMock = $this->getMockBuilder(Helpers\Misc::class)
            ->setMethods(["commandExists"])
            ->getMock();
        $this->serverDiagnostic = new ServerDiagnostic();

        $this->serverDiagnostic->setHelperMisc($this->helperMiscMock);
    }
    
    /**
     * @test
     * @group unit-utils
     */
    public function checkPluginRequirementsReturnsArray(): void
    {
        $this->helperMiscMock->expects($this->any())
            ->method("commandExists")
            ->will($this->returnValue(true));

        $this->assertInternalType("array", $this->serverDiagnostic->checkPluginRequirements());
    }

    /**
     * @test
     * @group unit-utils
     */
    public function checkPluginRequirementsReturnsNotEmptyArray(): void
    {
        $this->helperMiscMock->expects($this->any())
            ->method("commandExists")
            ->will($this->returnValue(true));
        
        $this->assertNotEmpty($this->serverDiagnostic->checkPluginRequirements());
    }
}
