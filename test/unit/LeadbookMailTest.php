<?php

namespace LeadbookMail\Test;

use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use LeadbookMail\LeadbookMail;
use LeadbookMail\LeadbookMailPromise;
use GuzzleHttp\Promise\FulfilledPromise as GuzzleFulfilledPromise;
use GuzzleHttp\Promise\RejectedPromise as GuzzleRejectedPromise;
use Http\Promise\Promise as GuzzleAdapterPromise;
use Mockery;
use LeadbookMail\Test\TestUtils\ClassUtils;

class LeadbookMailTest extends \PHPUnit_Framework_TestCase
{
    /** @var ClassUtils */
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
            ['address' => 'faysal@leadbook.com'],
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

        $this->resource = new LeadbookMail($this->clientMock, ['key' => 'LEADBOOKMAIL_API_KEY']);
        self::$utils = new ClassUtils($this->resource);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testRequest()
    {
        $responseMock = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $this->resource->setOptions(['async' => false]);
        $this->clientMock->shouldReceive('sendRequest')->andReturn($responseMock);
        $this->assertInstanceOf('LeadbookMail\LeadbookMailResponse', $this->resource->request('POST', 'transmissions', $this->postTransmissionPayload));

        $promiseMock = Mockery::mock('Http\Promise\Promise');
        $this->resource->setOptions(['async' => true]);
        $this->clientMock->shouldReceive('sendAsyncRequest')->andReturn($promiseMock);
        $this->assertInstanceOf('LeadbookMail\LeadbookMailPromise', $this->resource->request('GET', 'transmissions', $this->getTransmissionPayload));
    }

    public function testSuccessfulSyncRequest()
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

        $response = $this->resource->syncRequest('POST', 'transmissions', $this->postTransmissionPayload);

        $this->assertEquals($responseBody, $response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUnsuccessfulSyncRequest()
    {
        $exceptionMock = Mockery::mock('Http\Client\Exception\HttpException');

        $responseBody = ['results' => 'failed'];

        $this->clientMock->shouldReceive('sendRequest')->
            once()->
            with(Mockery::type('GuzzleHttp\Psr7\Request'))->
            andThrow($exceptionMock);

        $exceptionMock->shouldReceive('getResponse->getStatusCode')->andReturn(500);
        $exceptionMock->shouldReceive('getResponse->getBody->__toString')->andReturn(json_encode($responseBody));

        try {
            $this->resource->syncRequest('POST', 'transmissions', $this->postTransmissionPayload);
        } catch (\Exception $e) {
            $this->assertEquals($responseBody, $e->getBody());
            $this->assertEquals(500, $e->getCode());
        }
    }

    public function testSuccessfulAsyncRequestWithWait()
    {
        $promiseMock = Mockery::mock('Http\Promise\Promise');
        $responseMock = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $responseBodyMock = Mockery::mock();

        $responseBody = ['results' => 'yay'];

        $this->clientMock->shouldReceive('sendAsyncRequest')->
            once()->
            with(Mockery::type('GuzzleHttp\Psr7\Request'))->
            andReturn($promiseMock);

        $promiseMock->shouldReceive('wait')->andReturn($responseMock);

        $responseMock->shouldReceive('getStatusCode')->andReturn(200);
        $responseMock->shouldReceive('getBody')->andReturn($responseBodyMock);
        $responseBodyMock->shouldReceive('__toString')->andReturn(json_encode($responseBody));

        $promise = $this->resource->asyncRequest('POST', 'transmissions', $this->postTransmissionPayload);

        $response = $promise->wait();

        $this->assertEquals($responseBody, $response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUnsuccessfulAsyncRequestWithWait()
    {
        $promiseMock = Mockery::mock('Http\Promise\Promise');
        $exceptionMock = Mockery::mock('Http\Client\Exception\HttpException');

        $responseBody = ['results' => 'failed'];

        $this->clientMock->shouldReceive('sendAsyncRequest')->
            once()->
            with(Mockery::type('GuzzleHttp\Psr7\Request'))->
            andReturn($promiseMock);

        $promiseMock->shouldReceive('wait')->andThrow($exceptionMock);

        $exceptionMock->shouldReceive('getResponse->getStatusCode')->andReturn(500);
        $exceptionMock->shouldReceive('getResponse->getBody->__toString')->andReturn(json_encode($responseBody));

        $promise = $this->resource->asyncRequest('POST', 'transmissions', $this->postTransmissionPayload);

        try {
            $response = $promise->wait();
        } catch (\Exception $e) {
            $this->assertEquals($responseBody, $e->getBody());
            $this->assertEquals(500, $e->getCode());
        }
    }

    public function testSuccessfulAsyncRequestWithThen()
    {
        $responseBody = ['results' => 'yay'];
        $responseMock = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $responseBodyMock = Mockery::mock();
        $responseMock->shouldReceive('getStatusCode')->andReturn(200);
        $responseMock->shouldReceive('getBody')->andReturn($responseBodyMock);
        $responseBodyMock->shouldReceive('__toString')->andReturn(json_encode($responseBody));

        $guzzlePromise = new GuzzleFulfilledPromise($responseMock);

        $promise = new LeadbookMailPromise(new GuzzleAdapterPromise($guzzlePromise, $this->resource->buildRequest('POST', 'transmissions', $this->postTransmissionPayload,[])));

        $promise->then(function ($exception) use ($responseBody) {
            $this->assertEquals(200, $exception->getStatusCode());
            $this->assertEquals($responseBody, $exception->getBody());
        }, null)->wait();
    }

    public function testUnsuccessfulAsyncRequestWithThen()
    {
        $responseBody = ['results' => 'failed'];
        $exceptionMock = Mockery::mock('Http\Client\Exception\HttpException');
        $exceptionMock->shouldReceive('getResponse->getStatusCode')->andReturn(500);
        $exceptionMock->shouldReceive('getResponse->getBody->__toString')->andReturn(json_encode($responseBody));

        $guzzlePromise = new GuzzleRejectedPromise($exceptionMock);

        $promise = new LeadbookMailPromise(new GuzzleAdapterPromise($guzzlePromise, $this->resource->buildRequest('POST', 'transmissions', $this->postTransmissionPayload, [])));

        $promise->then(null, function ($exception) use ($responseBody) {
            $this->assertEquals(500, $exception->getCode());
            $this->assertEquals($responseBody, $exception->getBody());
        })->wait();
    }

    public function testPromise()
    {
        $promiseMock = Mockery::mock('Http\Promise\Promise');

        $this->clientMock->shouldReceive('sendAsyncRequest')->
            once()->
            with(Mockery::type('GuzzleHttp\Psr7\Request'))->
            andReturn($promiseMock);

        $promise = $this->resource->asyncRequest('POST', 'transmissions', $this->postTransmissionPayload);

        $promiseMock->shouldReceive('getState')->twice()->andReturn('pending');
        $this->assertEquals($promiseMock->getState(), $promise->getState());

        $promiseMock->shouldReceive('getState')->once()->andReturn('rejected');
        $this->assertEquals('rejected', $promise->getState());
    }

    /**
     * @expectedException \Exception
     */
    public function testUnsupportedAsyncRequest()
    {
        $this->resource->setHttpClient(Mockery::mock('Http\Client\HttpClient'));

        $this->resource->asyncRequest('POST', 'transmissions', $this->postTransmissionPayload);
    }

    public function testGetHttpHeaders()
    {
        $headers = $this->resource->getHttpHeaders([
            'Custom-Header' => 'testing',
        ]);

        $version = self::$utils->getProperty($this->resource, 'version');

        $this->assertEquals('LEADBOOKMAIL_API_KEY', $headers['Authorization']);
        $this->assertEquals('application/json', $headers['Content-Type']);
        $this->assertEquals('testing', $headers['Custom-Header']);
        $this->assertEquals('php-LeadbookMail/'.$version, $headers['User-Agent']);
    }

    public function testGetUrl()
    {
        $url = 'http://mailserver.leadbook.com/?key=value 1,value 2,value 3';
        $testUrl = $this->resource->getUrl('transmissions', ['key' => ['value 1', 'value 2', 'value 3']]);
        $this->assertEquals($url, $testUrl);
    }

    public function testSetHttpClient()
    {
        $mock = Mockery::mock(HttpClient::class);
        $this->resource->setHttpClient($mock);
        $this->assertEquals($mock, self::$utils->getProperty($this->resource, 'httpClient'));
    }

    public function testSetHttpAsyncClient()
    {
        $mock = Mockery::mock(HttpAsyncClient::class);
        $this->resource->setHttpClient($mock);
        $this->assertEquals($mock, self::$utils->getProperty($this->resource, 'httpClient'));
    }

    /**
     * @expectedException \Exception
     */
    public function testSetHttpClientException()
    {
        $this->resource->setHttpClient(new \stdClass());
    }

    public function testSetOptionsStringKey()
    {
        $this->resource->setOptions('LEADBOOKMAIL_API_KEY');
        $options = self::$utils->getProperty($this->resource, 'options');
        $this->assertEquals('LEADBOOKMAIL_API_KEY', $options['key']);
    }

    /**
     * @expectedException \Exception
     */
    public function testSetBadOptions()
    {
        self::$utils->setProperty($this->resource, 'options', []);
        $this->resource->setOptions(['not' => 'LEADBOOKMAIL_API_KEY']);
    }

    public function testSetMessageFactory()
    {
        $messageFactory = Mockery::mock(MessageFactory::class);
        $this->resource->setMessageFactory($messageFactory);

        $this->assertEquals($messageFactory, self::$utils->getMethod('getMessageFactory')->invoke($this->resource));
    }
}
