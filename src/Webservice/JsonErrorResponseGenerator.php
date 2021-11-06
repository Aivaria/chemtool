<?php

namespace Chemtool\Webservice;

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Stratigility\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class JsonErrorResponseGenerator
{
    public function __construct(protected bool $debugmode)
    {

    }

    public function __invoke(
        Throwable              $e,
        ServerRequestInterface $request,
        ResponseInterface      $response): ResponseInterface
    {
        $statusCode = Utils::getStatusCode($e, $response);
        $responseData = [
            'status' => $statusCode,
            'error' => [
                'type' => 'Exception',
                'code' => $e->getCode(),
            ]
        ];
        if ($this->debugmode) {
            $responseData['debug'] = $this->prepareStackTrace($e);
        }
        return new JsonResponse($responseData, $statusCode);
    }

    protected function prepareStackTrace(Throwable $e): array
    {
        $result = [];
        do {
            $result[] = [
                'type' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'tace' => $e->getTrace()
            ];
        } while($e = $e->getPrevious());
        return $result;
    }
}