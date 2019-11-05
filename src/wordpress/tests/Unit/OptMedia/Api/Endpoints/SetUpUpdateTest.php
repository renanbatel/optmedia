<?php

namespace OptMedia\Tests\Unit\OptMedia\Api\Endpoints;

use WP_UnitTestCase;

use OptMedia\Constants;
use OptMedia\Tests\Resources\Utils\REST;
use OptMedia\Api\Endpoints\SetUpUpdate;
use OptMedia\Settings\Option;

class SetUpUpdateTest extends WP_UnitTestCase
{
    protected $setUpUpdate;
    protected $optionMock;

    public function setUp()
    {
        $this->optionMock = $this->getMockBuilder(Option::class)
            ->setMethods(["updateOption"])
            ->getMock();
        $this->setUpUpdate = new SetUpUpdate();

        $this->setUpUpdate->setOption($this->optionMock);
    }

    /**
     * @test
     * @group unit-api
     */
    public function postIsValidated()
    {
        $this->assertTrue($this->setUpUpdate->postIsValid(["isSetUp" => false]));
        $this->assertTrue($this->setUpUpdate->postIsValid(["isSetUp" => true]));
        $this->assertFalse($this->setUpUpdate->postIsValid([]));
    }

    /**
     * @test
     * @group unit-api
     */
    public function validPostRequestIsHandled()
    {
        $body = [
            "isSetUp" => true,
        ];

        $this->optionMock->expects($this->once())
            ->method("updateOption")
            ->with(
                $this->equalTo(Constants::PLUGIN_IS_SETUP),
                $this->equalTo($body["isSetUp"])
            )
            ->will($this->returnValue(true));


        $response = $this->setUpUpdate->post(REST::setUpJSONRequest($body));

        $this->assertEquals(200, $response->status);
        $this->assertEquals($body["isSetUp"], $response->data[Constants::PLUGIN_IS_SETUP]);
        $this->assertTrue($response->data["success"]);
    }

    /**
     * @test
     * @group unit-api
     */
    public function validPostRequestErrorIsHandled()
    {
        $body = [
            "isSetUp" => true,
        ];

        $this->optionMock->expects($this->once())
            ->method("updateOption")
            ->with(
                $this->equalTo(Constants::PLUGIN_IS_SETUP),
                $this->equalTo($body["isSetUp"])
            )
            ->will($this->returnValue(false));


        $response = $this->setUpUpdate->post(REST::setUpJSONRequest($body));

        $this->assertEquals(500, $response->status);
        $this->assertTrue($response->data["error"]);
    }

    /**
     * @test
     * @group unit-api
     */
    public function invalidPostIsHandled()
    {
        $response = $this->setUpUpdate->post(REST::setUpJSONRequest([
            "foo" => "bar",
        ]));

        $this->assertEquals(400, $response->status);
        $this->assertTrue($response->data["error"]);
    }
}
