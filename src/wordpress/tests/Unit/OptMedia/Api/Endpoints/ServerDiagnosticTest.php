<?php

namespace OptMedia\Tests\Unit\OptMedia\Api\Endpoints;

use WP_UnitTestCase;

use OptMedia\Api\Endpoints\ServerDiagnostic;

class ServerDiagnosticTest extends WP_UnitTestCase
{
    protected $serverDiagnosticUtilMock;
    protected $serverDiagnostic;

    public function setUp()
    {
        $this->serverDiagnosticUtilMock = $this->getMockBuilder("\OptMedia\Utils\ServerDiagnostic")
            ->setMethods(["checkPluginRequirements"])
            ->getMock();
        $this->serverDiagnostic = new ServerDiagnostic();

        $this->serverDiagnostic->setServerDiagnostic($this->serverDiagnosticUtilMock);
    }

    /**
     * @test
     * @group unit-api
     */
    public function getRequestIsHandled()
    {
        $diagnostic = [ "foo" => "bar" ];
        $this->serverDiagnosticUtilMock->expects($this->once())
            ->method("checkPluginRequirements")
            ->will($this->returnValue($diagnostic));

        $response = $this->serverDiagnostic->get();

        $this->assertEquals(200, $response->status);
        $this->assertEquals($diagnostic, $response->data["diagnostic"]);
        $this->assertTrue($response->data["success"]);
    }

     /**
     * @test
     * @group unit-api
     */
    public function getRequestErrorIsHandled()
    {
        $this->serverDiagnosticUtilMock->expects($this->once())
            ->method("checkPluginRequirements")
            ->will($this->returnValue([]));

        $response = $this->serverDiagnostic->get();

        $this->assertEquals(500, $response->status);
        $this->assertTrue($response->data["error"]);
    }
}
