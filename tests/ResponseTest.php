<?php

namespace Shuttle\Tests;

use PHPUnit\Framework\TestCase;
use Shuttle\Response;
use Shuttle\ResponseStatus;
use Shuttle\Stream\BufferStream;

/**
 * @covers Shuttle\Response
 * @covers Shuttle\ResponseStatus
 * @covers Shuttle\MessageAbstract
 * @covers Shuttle\Stream\BufferStream
 */
class ResponseTest extends TestCase
{
    public function test_reason_phrase_set_on_constructor()
    {
        $response = (new Response)->withStatus(200);
        $this->assertNotEmpty($response->getReasonPhrase());
    }
    
    public function test_with_status_code_saves_data()
    {
        $response = (new Response)->withStatus(200);
        $response = $response->withStatus(404, "Page Not Found");

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Not Found", $response->getReasonPhrase());
    }

    public function test_with_status_code_resolves_phrase_if_none_given()
    {
        $response = (new Response)->withStatus(404);
        $this->assertEquals(ResponseStatus::getPhrase(404), $response->getReasonPhrase());
    }

    public function test_with_status_code_is_immutable()
    {
        $response = (new Response)->withStatus(200);
        $newResponse = $response->withStatus(404);
        $this->assertNotEquals($response, $newResponse);
    }

    public function test_1xx_responses_are_considered_successful()
    {
        $response = (new Response)->withStatus(100);
        $this->assertTrue($response->isSuccessful());
    }

    public function test_2xx_responses_are_considered_successful()
    {
        $response = (new Response)->withStatus(201);
        $this->assertTrue($response->isSuccessful());
    }

    public function test_3xx_responses_are_considered_successful()
    {
        $response = (new Response)->withStatus(304);
        $this->assertTrue($response->isSuccessful());
    }

    public function test_4xx_responses_are_considered_unsuccessful()
    {
        $response = (new Response)->withStatus(422);
        $this->assertFalse($response->isSuccessful());
    }

    public function test_5xx_responses_are_considered_unsuccessful()
    {
        $response = (new Response)->withStatus(503);
        $this->assertFalse($response->isSuccessful());
    }

    public function test_make_factory()
    {
        $response = Response::make(
            201,
            new BufferStream("OK"),
            [
                "X-Foo" =>"Bar",
            ],
            2
        );

        $this->assertTrue(($response instanceof Response));
    }
}