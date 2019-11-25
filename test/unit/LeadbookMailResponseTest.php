<?php

namespace LeadbookMail\Test;

use LeadbookMail\LeadbookMailResponse;
use Mockery;

class LeadbookMailResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * (non-PHPdoc).
     *
     * @before
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->returnValue = 'some_value_to_return';
        $this->responseMock = Mockery::mock('Psr\Http\Message\ResponseInterface');
    }

    public function testGetProtocolVersion()
    {
        $this->responseMock->shouldReceive('getProtocolVersion')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->getProtocolVersion(), $leadbookmailResponse->getProtocolVersion());
    }

    public function testWithProtocolVersion()
    {
        $param = 'protocol version';

        $this->responseMock->shouldReceive('withProtocolVersion')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->withProtocolVersion($param), $leadbookmailResponse->withProtocolVersion($param));
    }

    public function testGetHeaders()
    {
        $this->responseMock->shouldReceive('getHeaders')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->getHeaders(), $leadbookmailResponse->getHeaders());
    }

    public function testHasHeader()
    {
        $param = 'header';

        $this->responseMock->shouldReceive('hasHeader')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->hasHeader($param), $leadbookmailResponse->hasHeader($param));
    }

    public function testGetHeader()
    {
        $param = 'header';

        $this->responseMock->shouldReceive('getHeader')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->getHeader($param), $leadbookmailResponse->getHeader($param));
    }

    public function testGetHeaderLine()
    {
        $param = 'header';

        $this->responseMock->shouldReceive('getHeaderLine')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->getHeaderLine($param), $leadbookmailResponse->getHeaderLine($param));
    }

    public function testWithHeader()
    {
        $param = 'header';
        $param2 = 'value';

        $this->responseMock->shouldReceive('withHeader')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->withHeader($param, $param2), $leadbookmailResponse->withHeader($param, $param2));
    }

    public function testWithAddedHeader()
    {
        $param = 'header';
        $param2 = 'value';

        $this->responseMock->shouldReceive('withAddedHeader')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->withAddedHeader($param, $param2), $leadbookmailResponse->withAddedHeader($param, $param2));
    }

    public function testWithoutHeader()
    {
        $param = 'header';

        $this->responseMock->shouldReceive('withoutHeader')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->withoutHeader($param), $leadbookmailResponse->withoutHeader($param));
    }

    public function testWithBody()
    {
        $param = Mockery::mock('Psr\Http\Message\StreamInterface');

        $this->responseMock->shouldReceive('withBody')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->withBody($param), $leadbookmailResponse->withBody($param));
    }

    public function testGetStatusCode()
    {
        $this->responseMock->shouldReceive('getStatusCode')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->getStatusCode(), $leadbookmailResponse->getStatusCode());
    }

    public function testWithStatus()
    {
        $param = 'status';

        $this->responseMock->shouldReceive('withStatus')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->withStatus($param), $leadbookmailResponse->withStatus($param));
    }

    public function testGetReasonPhrase()
    {
        $this->responseMock->shouldReceive('getReasonPhrase')->andReturn($this->returnValue);
        $leadbookmailResponse = new LeadbookMailResponse($this->responseMock);
        $this->assertEquals($this->responseMock->getReasonPhrase(), $leadbookmailResponse->getReasonPhrase());
    }
}
