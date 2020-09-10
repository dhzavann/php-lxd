<?php

namespace Opensaucesystems\Lxd\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Http\Client\Exception\HttpException;
use Opensaucesystems\Lxd\Exception\OperationException;
use Opensaucesystems\Lxd\Exception\AuthenticationFailedException;
use Opensaucesystems\Lxd\Exception\NotFoundException;
use Opensaucesystems\Lxd\Exception\ConflictException;
use Opensaucesystems\Lxd\Exception\ServerException;

/**
 * Handle LXD errors
 *
 */
class LxdExceptionThower implements Plugin
{
    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        $promise = $next($request);

        return $promise->then(function (ResponseInterface $response) use ($request) {
            if ($response->getStatusCode() < 400) {
                return $response;
            }

            if (401 === $response->getStatusCode()) {
                throw new OperationException($request, $response);
            }

            if (401 === $response->getStatusCode()) {
                throw new OperationException($request, $response);
            }

            if (403 === $response->getStatusCode()) {
                throw new AuthenticationFailedException($request, $response);
            }

            if (404 === $response->getStatusCode()) {
                throw new NotFoundException($request, $response);
            }

            if (409 === $response->getStatusCode()) {
                throw new ConflictException($request, $response);
            }

            throw new ServerException("Unknown error; status code was {$response->getStatusCode()}");
        }, function (\Exception $e) use ($request) {
            throw $e;
        });
    }
}
