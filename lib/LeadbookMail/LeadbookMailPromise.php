<?php

namespace LeadbookMail;

use Http\Promise\Promise as HttpPromise;

class LeadbookMailPromise implements HttpPromise
{
    /**
     * HttpPromise to be wrapped by LeadbookMailPromise.
     */
    private $promise;

    /**
     * set the promise to be wrapped.
     *
     * @param HttpPromise $promise
     */
    public function __construct(HttpPromise $promise)
    {
        $this->promise = $promise;
    }

    /**
     * Hand off the response functions to the original promise and return a custom response or exception.
     *
     * @param callable $onFulfilled - function to be called if the promise is fulfilled
     * @param callable $onRejected  - function to be called if the promise is rejected
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->promise->then(function ($response) use ($onFulfilled) {
            if (isset($onFulfilled)) {
                $onFulfilled(new LeadbookMailResponse($response));
            }
        }, function ($exception) use ($onRejected) {
            if (isset($onRejected)) {
                $onRejected(new LeadbookMailException($exception));
            }
        });
    }

    /**
     * Hand back the state.
     *
     * @return $state - returns the state of the promise
     */
    public function getState()
    {
        return $this->promise->getState();
    }

    /**
     * Wraps the wait function and returns a custom response or throws a custom exception.
     *
     * @param bool $unwrap
     *
     * @return LeadbookMailResponse
     *
     * @throws LeadbookMailException
     */
    public function wait($unwrap = true)
    {
        try {
            $response = $this->promise->wait($unwrap);

            return $response ? new LeadbookMailResponse($response) : $response;
        } catch (\Exception $exception) {
            throw new LeadbookMailException($exception);
        }
    }
}
