<?php

namespace LeadbookMail;

class Resource
{
    /**
     * LeadbookMail object used to make requests.
     */
    protected $leadbookmail;

    /**
     * The api endpoint that gets prepended to all requests send through this resource.
     */
    protected $endpoint;

    /**
     * Sets up the Resource.
     *
     * @param LeadbookMail $LeadbookMail - the LeadbookMail instance that this resource is attached to
     * @param string    $endpoint  - the endpoint that this resource wraps
     */
    public function __construct(LeadbookMail $leadbookmail, $endpoint)
    {
        $this->leadbookmail = $leadbookmail;
        $this->endpoint = $endpoint;
    }

    /**
     * Sends get request to API at the set endpoint.
     *
     * @see LeadbookMail->request()
     */
    public function get($uri = '', $payload = [], $headers = [])
    {
        return $this->request('GET', $uri, $payload, $headers);
    }

    /**
     * Sends put request to API at the set endpoint.
     *
     * @see LeadbookMail->request()
     */
    public function put($uri = '', $payload = [], $headers = [])
    {
        return $this->request('PUT', $uri, $payload, $headers);
    }

    /**
     * Sends post request to API at the set endpoint.
     *
     * @see LeadbookMail->request()
     */
    public function post($payload = [], $headers = [])
    {
        return $this->request('POST', '', $payload, $headers);
    }

    /**
     * Sends delete request to API at the set endpoint.
     *
     * @see LeadbookMail->request()
     */
    public function delete($uri = '', $payload = [], $headers = [])
    {
        return $this->request('DELETE', $uri, $payload, $headers);
    }

    /**
     * Sends requests to LeadbookMail object to the resource endpoint.
     *
     * @see LeadbookMail->request()
     *
     * @return LeadbookMailPromise or LeadbookMailResponse depending on sync or async request
     */
    public function request($method = 'GET', $uri = '', $payload = [], $headers = [])
    {
        if (is_array($uri)) {
            $headers = $payload;
            $payload = $uri;
            $uri = '';
        }

        $uri = rtrim($this->endpoint.'/'.$uri,'/');
        return $this->leadbookmail->request($method, $uri, $payload, $headers);
    }
}
