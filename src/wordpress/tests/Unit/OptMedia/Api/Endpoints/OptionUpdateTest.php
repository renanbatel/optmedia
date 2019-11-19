<?php

namespace OptMedia\Tests\Unit\OptMedia\Api\Endpoints;

use WP_UnitTestCase;

use OptMedia\Constants;
use OptMedia\Tests\Resources\Utils\REST;
use OptMedia\Api\Endpoints\OptionUpdate;
use OptMedia\Settings\Option;

class OptionUpdateTest extends WP_UnitTestCase
{
    protected $optionUpdate;
    protected $optionMock;

    public function setUp()
    {
        $this->optionMock = $this->getMockBuilder(Option::class)
            ->setMethods(["updateOption"])
            ->getMock();
        $this->optionUpdate = OptionUpdate::factory($this->optionMock);
    }

    /**
     * @test
     * @group unit-api
     */
    public function postIsValidated()
    {
        $this->assertTrue($this->optionUpdate->postIsValid([
            "key" => Constants::PLUGIN_IMAGE_FORMATS,
            "value" => "foo",
        ]));
        $this->assertTrue($this->optionUpdate->postIsValid([
            "key" => Constants::PLUGIN_IMAGE_FORMATS,
            "value" => [ "foo", "bar" ],
        ]));
        $this->assertFalse($this->optionUpdate->postIsValid([
            "key" => "foo",
            "value" => "bar",
        ]));
        $this->assertFalse($this->optionUpdate->postIsValid([
            "key" => Constants::PLUGIN_IMAGE_FORMATS,
            "value" => [],
        ]));
    }

    /**
     * @test
     * @group unit-api
     */
    public function validPostRequestIsHandled()
    {
        $body = [
            "key" => Constants::PLUGIN_IMAGE_FORMATS,
            "value" => "foo",
        ];

        $this->optionMock->expects($this->once())
            ->method("updateOption")
            ->with(
                $this->equalTo($body["key"]),
                $this->equalTo($body["value"])
            )
            ->will($this->returnValue(true));


        $response = $this->optionUpdate->post(REST::setUpJSONRequest($body));

        $this->assertEquals(200, $response->status);
        $this->assertTrue($response->data["success"]);
        $this->assertEquals($body["key"], $response->data["option"]["key"]);
        $this->assertEquals($body["value"], $response->data["option"]["value"]);
    }

    /**
     * @test
     * @group unit-api
     */
    public function validPostRequestErrorIsHandled()
    {
        $body = [
            "key" => Constants::PLUGIN_IMAGE_FORMATS,
            "value" => "bar",
        ];

        $this->optionMock->expects($this->once())
            ->method("updateOption")
            ->with(
                $this->equalTo($body["key"]),
                $this->equalTo($body["value"])
            )
            ->will($this->returnValue(false));


        $response = $this->optionUpdate->post(REST::setUpJSONRequest($body));

        $this->assertEquals(500, $response->status);
        $this->assertTrue($response->data["error"]);
    }

    /**
     * @test
     * @group unit-api
     */
    public function invalidPostIsHandled()
    {
        $response = $this->optionUpdate->post(REST::setUpJSONRequest([
            "key" => "foo",
            "value" => "bar",
        ]));

        $this->assertEquals(400, $response->status);
        $this->assertTrue($response->data["error"]);
    }
}
