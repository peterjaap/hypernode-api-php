<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use GuzzleHttp\Psr7\Response;
use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;
use Hypernode\Api\HypernodeClientTestCase;

class BrancherAppTest extends HypernodeClientTestCase
{
    public function testListBrancherApp()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'name' => 'johndoe-eph123456',
                'parent' => 'johndoe',
                'type' => 'brancher',
                'branchers' => []
            ])),
        );

        $branchers = $this->client->brancherApp->list('johndoe');

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/brancher/app/johndoe/', $request->getUri());
        $this->assertEquals([], $branchers);
    }

    public function testListBrancherAppRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->brancherApp->list('johndoe');
    }

    public function testListBrancherAppRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->brancherApp->list('johndoe');
    }

    public function testCreateBrancherApp()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'name' => 'johndoe-eph123456',
                'parent' => 'johndoe',
                'type' => 'brancher',
            ])),
        );

        $brancherAppName = $this->client->brancherApp->create('johndoe');

        $request = $this->responses->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/v2/brancher/app/johndoe/', $request->getUri());
        $this->assertEquals('johndoe-eph123456', $brancherAppName);
    }

    public function testCreateBrancherAppWithData()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'name' => 'johndoe-eph123456',
                'parent' => 'johndoe',
                'type' => 'brancher',
            ])),
        );

        $brancherAppName = $this->client->brancherApp->create(
            'johndoe',
            ['labels' => ['mybranchernode', 'mylabel=myvalue']]
        );

        $request = $this->responses->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/v2/brancher/app/johndoe/', $request->getUri());
        $this->assertEquals('johndoe-eph123456', $brancherAppName);
        $this->assertJson((string)$request->getBody());
        $this->assertEquals(
            ['labels' => ['mybranchernode', 'mylabel=myvalue']],
            json_decode((string)$request->getBody(), true)
        );
    }

    public function testCreateBrancherAppRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->brancherApp->create('johndoe');
    }

    public function testCreateBrancherAppRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->brancherApp->create('johndoe');
    }

    public function testCancelBrancherApp()
    {
        $this->responses->append(
            new Response(204, [], null),
        );

        $this->client->brancherApp->cancel('johndoe-eph123456');

        $request = $this->responses->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/v2/app/johndoe-eph123456/cancel/', $request->getUri());
    }

    public function testCancelBrancherAppRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->brancherApp->cancel('johndoe');
    }

    public function testCancelBrancherAppRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->brancherApp->cancel('johndoe');
    }
}
