<?php

namespace OptMedia\Tests\Unit\OptMedia\Api\Endpoints;

use WP_UnitTestCase;

use OptMedia\Api\Endpoints\Options;
use OptMedia\Settings\Option;

class OptionsTest extends WP_UnitTestCase
{
    protected $options;
    protected $optionMock;

    public function setUp()
    {
        $this->optionMock = $this->getMockBuilder(Option::class)
            ->setMethods(["getOptions"])
            ->getMock();
        $this->options = new Options();

        $this->options->setOption($this->optionMock);
    }

    /**
     * @test
     * @group unit-api
     */
    public function getRequestIsHandled()
    {
        $options = [ "foo" => "bar" ];

        $this->optionMock->expects($this->once())
            ->method("getOptions")
            ->will($this->returnValue($options));

        $response = $this->options->get();

        $this->assertEquals(200, $response->status);
        $this->assertEquals($options, $response->data["options"]);
        $this->assertTrue($response->data["success"]);
    }

    /**
     * @test
     * @group unit-api
     */
    public function getRequestInternalErrorIsHandled()
    {
        $this->optionMock->expects($this->once())
            ->method("getOptions")
            ->will($this->returnValue([]));

        $response = $this->options->get();

        $this->assertEquals(500, $response->status);
        $this->assertTrue($response->data["error"]);
    }
}
