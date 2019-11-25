<?php

namespace LeadbookMail\Test;

use LeadbookMail\LeadbookMail;
use Mockery;
use LeadbookMail\Test\TestUtils\ClassUtils;

class TransmissionTest extends \PHPUnit_Framework_TestCase
{
    private static $utils;
    private $clientMock;
    /** @var LeadbookMail */
    private $resource;

    private $postTransmissionPayload = [
        'content' => [
            'from' => ['name' => 'LeadbookMail Team', 'email' => 'postmaster@sendmailfor.me'],
            'subject' => 'First Mailing From PHP',
            'text' => 'Congratulations, {{name}}!! You just sent your very first mailing!',
        ],
        'substitution_data' => ['name' => 'Avi'],
        'recipients' => [
            [
                'address' => [
                    'name' => 'Faysal',
                    'email' => 'faysal@leadbook.com',
                ],
            ],
            ['address' => 'test@example.com'],
        ],
        'cc' => [
            [
                'address' => [
                    'email' => 'hari@leadbook.com',
                ],
            ],
        ],
        'bcc' => [
            ['address' => 'Tarikul Islam <tarikul@leadbook.com>'],
        ],

    ];

    private $getTransmissionPayload = [
        'campaign_id' => 'thanksgiving',
    ];

    /**
     * (non-PHPdoc).
     *
     * @before
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        //setup mock for the adapter
        $this->clientMock = Mockery::mock('Http\Adapter\Guzzle6\Client');

        $this->resource = new LeadbookMail($this->clientMock, ['key' => 'LEADBOOKMAIL_API_KEY', 'async' => false]);
        self::$utils = new ClassUtils($this->resource);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidEmailFormat()
    {
        $this->postTransmissionPayload['recipients'][] = [
            'address' => 'invalid email format',
        ];

        $response = $this->resource->transmissions->post($this->postTransmissionPayload);
    }

    public function testGet()
    {
        $responseMock = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $responseBodyMock = Mockery::mock();

        $responseBody = ['results' => 'yay'];

        $this->clientMock->shouldReceive('sendRequest')->
            once()->
            with(Mockery::type('GuzzleHttp\Psr7\Request'))->
            andReturn($responseMock);

        $responseMock->shouldReceive('getStatusCode')->andReturn(200);
        $responseMock->shouldReceive('getBody')->andReturn($responseBodyMock);
        $responseBodyMock->shouldReceive('__toString')->andReturn(json_encode($responseBody));

        $response = $this->resource->transmissions->get($this->getTransmissionPayload);

        $this->assertEquals($responseBody, $response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPut()
    {
        $responseMock = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $responseBodyMock = Mockery::mock();

        $responseBody = ['results' => 'yay'];

        $this->clientMock->shouldReceive('sendRequest')->
            once()->
            with(Mockery::type('GuzzleHttp\Psr7\Request'))->
            andReturn($responseMock);

        $responseMock->shouldReceive('getStatusCode')->andReturn(200);
        $responseMock->shouldReceive('getBody')->andReturn($responseBodyMock);
        $responseBodyMock->shouldReceive('__toString')->andReturn(json_encode($responseBody));

        $response = $this->resource->transmissions->put($this->getTransmissionPayload);

        $this->assertEquals($responseBody, $response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPost()
    {
        $responseMock = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $responseBodyMock = Mockery::mock();

        $responseBody = ['results' => 'yay'];

        $this->clientMock->shouldReceive('sendRequest')->
            once()->
            with(Mockery::type('GuzzleHttp\Psr7\Request'))->
            andReturn($responseMock);

        $responseMock->shouldReceive('getStatusCode')->andReturn(200);
        $responseMock->shouldReceive('getBody')->andReturn($responseBodyMock);
        $responseBodyMock->shouldReceive('__toString')->andReturn(json_encode($responseBody));

        $response = $this->resource->transmissions->post($this->postTransmissionPayload);

        $this->assertEquals($responseBody, $response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDelete()
    {
        $responseMock = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $responseBodyMock = Mockery::mock();

        $responseBody = ['results' => 'yay'];

        $this->clientMock->shouldReceive('sendRequest')->
            once()->
            with(Mockery::type('GuzzleHttp\Psr7\Request'))->
            andReturn($responseMock);

        $responseMock->shouldReceive('getStatusCode')->andReturn(200);
        $responseMock->shouldReceive('getBody')->andReturn($responseBodyMock);
        $responseBodyMock->shouldReceive('__toString')->andReturn(json_encode($responseBody));

        $response = $this->resource->transmissions->delete($this->getTransmissionPayload);

        $this->assertEquals($responseBody, $response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testFormatPayload()
    {
        $correctFormattedPayload = json_decode('{"content":{"from":{"name":"LeadbookMail Team","email":"mail@leadbook.com"},"subject":"First Mailing From PHP","text":"Congratulations, {{name}}!! You just sent your very first mailing!","headers":{"CC":"faysal@leadbook.com"}},"substitution_data":{"name":"Faysal"},"recipients":[{"address":{"name":"Hari","email":"hari@leadbook.com"}},{"address":{"email":"test@example.com"}},{"address":{"email":"tarikul@leadbook.com","header_to":"\"Hari\" <hari@leadbook.com>"}},{"address":{"email":"tarikul@leadbook.com","header_to":"\"hari\" <hari@leadbook.com>"}}]}', true);

        $formattedPayload = $this->resource->transmissions->formatPayload($this->postTransmissionPayload);
        $this->assertEquals($correctFormattedPayload, $formattedPayload);
    }
}
