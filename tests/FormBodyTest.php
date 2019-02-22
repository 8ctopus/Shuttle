<?php

namespace Shuttle\Tests;

use PHPUnit\Framework\TestCase;
use Shuttle\Body\FormBody;

class FormBodyTest extends TestCase
{
    public function test_default_content_type()
    {
        $formBody = new FormBody([]);

        $this->assertEquals("application/x-www-form-urlencoded", $formBody->getContentType());
    }

    public function test_form_body_transformation()
    {
        $formBody = new FormBody(
            [
                "name" => "John Doe",
                "email" => "jdoe@example.com",
            ]
        );

        $this->assertEquals("name=John+Doe&email=jdoe%40example.com", $formBody->getContents());
    }
}