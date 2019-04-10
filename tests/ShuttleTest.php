<?php

namespace Shuttle\Tests;

use Shuttle\Body\BufferBody;
use Capsule\Response;
use Capsule\Stream\BufferStream;
use PHPUnit\Framework\TestCase;
use Shuttle\Handler\HandlerAbstract;
use Shuttle\Handler\MockHandler;
use Shuttle\Shuttle;

/**
 * @covers Shuttle\Shuttle
 * @covers Shuttle\Handler\MockHandler
 * @covers Shuttle\Handler\CurlHandler
 * @covers Shuttle\Body\BufferBody
 */
class ShuttleTest extends TestCase
{
    public function test_default_user_agent_prefix()
    {
        $this->assertEquals("Shuttle/1.0", SHUTTLE_USER_AGENT);
    }
    
    public function test_shuttle_creates_default_handler()
    {
        $shuttle = new Shuttle;
        $this->assertTrue($shuttle->getHandler() instanceof HandlerAbstract);
    }

    public function test_passing_non_handler_as_option_throws_exception()
    {
        $this->expectException(\Exception::class);

        $shuttle = new Shuttle([
            'handler' => 'NotAHandler',
        ]);
    }

    public function test_get_response_received()
    {
        $shuttle = new Shuttle([
            'handler' => new MockHandler([
                new Response(200, new BufferStream("OK"), ["Content-Type" => "text/plain"]),
            ])
        ]);

        $response = $shuttle->get("http://example.com");

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("OK", $response->getBody()->getContents());
        $this->assertTrue($response->hasHeader("Content-Type"));
        $this->assertEquals("Content-Type: text/plain", $response->getHeaderLine("Content-Type"));
    }

    public function test_post_response_received()
    {
        $shuttle = new Shuttle([
            'handler' => new MockHandler([
                new Response(201, new BufferStream("OK"), ["Content-Type" => "text/plain"]),
            ])
        ]);

        $response = $shuttle->post("http://example.com", new BufferBody("foo"));

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals("OK", $response->getBody()->getContents());
        $this->assertTrue($response->hasHeader("Content-Type"));
        $this->assertEquals("Content-Type: text/plain", $response->getHeaderLine("Content-Type"));
    }

    public function test_patch_response_received()
    {
        $shuttle = new Shuttle([
            'handler' => new MockHandler([
                new Response(200, new BufferStream("OK"), ["Content-Type" => "text/plain"]),
            ])
        ]);

        $response = $shuttle->patch("http://example.com", new BufferBody("foo"));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("OK", $response->getBody()->getContents());
        $this->assertTrue($response->hasHeader("Content-Type"));
        $this->assertEquals("Content-Type: text/plain", $response->getHeaderLine("Content-Type"));
    }

    public function test_put_response_received()
    {
        $shuttle = new Shuttle([
            'handler' => new MockHandler([
                new Response(200, new BufferStream("OK"), ["Content-Type" => "text/plain"]),
            ])
        ]);

        $response = $shuttle->put("http://example.com", new BufferBody("foo"));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("OK", $response->getBody()->getContents());
        $this->assertTrue($response->hasHeader("Content-Type"));
        $this->assertEquals("Content-Type: text/plain", $response->getHeaderLine("Content-Type"));
    }

    public function test_delete_response_received()
    {
        $shuttle = new Shuttle([
            'handler' => new MockHandler([
                new Response(204, new BufferStream(), ["Content-Type" => "text/plain"]),
            ])
        ]);

        $response = $shuttle->delete("http://example.com");

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals("", $response->getBody()->getContents());
    }

    public function test_head_response_received()
    {
        $shuttle = new Shuttle([
            'handler' => new MockHandler([
                new Response(200, new BufferStream("")),
            ])
        ]);

        $response = $shuttle->head("http://example.com");

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("", $response->getBody()->getContents());
    }

    public function test_options_response_received()
    {
        $shuttle = new Shuttle([
            'handler' => new MockHandler([
                new Response(200, new BufferStream("")),
            ])
        ]);

        $response = $shuttle->options("http://example.com");

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("", $response->getBody()->getContents());
    }
}