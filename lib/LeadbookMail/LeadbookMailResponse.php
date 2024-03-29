<?php

namespace LeadbookMail;

use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\StreamInterface as StreamInterface;

class LeadbookMailResponse implements ResponseInterface
{
    /**
     * ResponseInterface to be wrapped by LeadbookMailResponse.
     */
    private $response;

    /**
     * set the response to be wrapped.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Returns the body.
     *
     * @return array $body - the json decoded body from the http response
     */
    public function getBody()
    {
        $body = $this->response->getBody();
        $body_string = $body->__toString();

        $json = json_decode($body_string, true);

        return $json;
    }

    /**
     * pass these down to the response given in the constructor.
     */
    public function getProtocolVersion()
    {
        return $this->response->getProtocolVersion();
    }

    public function withProtocolVersion($version)
    {
        return $this->response->withProtocolVersion($version);
    }

    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    public function hasHeader($name)
    {
        return $this->response->hasHeader($name);
    }

    public function getHeader($name)
    {
        return $this->response->getHeader($name);
    }

    public function getHeaderLine($name)
    {
        return $this->response->getHeaderLine($name);
    }

    public function withHeader($name, $value)
    {
        return $this->response->withHeader($name, $value);
    }

    public function withAddedHeader($name, $value)
    {
        return $this->response->withAddedHeader($name, $value);
    }

    public function withoutHeader($name)
    {
        return $this->response->withoutHeader($name);
    }

    public function withBody(StreamInterface $body)
    {
        return $this->response->withBody($body);
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        return $this->response->withStatus($code, $reasonPhrase);
    }

    public function getReasonPhrase()
    {
        return $this->response->getReasonPhrase();
    }
}
