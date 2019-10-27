<?php

namespace Tests;

use Symfony\Component\HttpFoundation\Request;

class ControllerTest extends BaseBundleTestCase
{
    public function testRequestEmulation()
    {
        $jsonBody = '{"test": "value"}';
        $request = Request::create('http://localhost/register', 'POST', [], [], [], [], $jsonBody);
        $response = $this->getKernel()->handle($request);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('{"properties":{"test":["Key is not defined in model."]}}', $response->getContent());
    }
}