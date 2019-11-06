<?php

namespace OptMedia\Tests\Unit\OptMedia\Api\Resources;

use WP_UnitTestCase;

use OptMedia\Api\Resources\Validator;

class ValidatorTest extends WP_UnitTestCase
{
    /**
     * @test
     * @group unit-api
     */
    public function emptyIsValidated()
    {
        $this->assertTrue(Validator::isEmpty(""));
        $this->assertTrue(Validator::isEmpty(["foo" => ""], "foo"));
        $this->assertTrue(Validator::isEmpty([], "foo"));
        $this->assertFalse(Validator::isEmpty("foo"));
        $this->assertFalse(Validator::isEmpty(["foo" => "bar"], "foo"));
    }
}
