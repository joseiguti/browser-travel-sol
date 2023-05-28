<?php

declare(strict_types=1);

namespace App\Database;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Database implements MiddlewareInterface
{
    private DatabaseModel $databaseModel;

    /**
     * Constructor de la clase Database.
     *
     * @param DatabaseModel $databaseModel Modelo de base de datos.
     */
    public function __construct(DatabaseModel $databaseModel)
    {
        $this->databaseModel = $databaseModel;
    }

    /**
     * Procesa la solicitud y devuelve una respuesta.
     *
     * @param ServerRequestInterface  $request  Objeto de solicitud HTTP.
     * @param RequestHandlerInterface $handler  Objeto manejador de solicitudes.
     * @return ResponseInterface Objeto de respuesta HTTP.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getAttribute('weatherId')) {
            $weatherId = $request->getAttribute('weatherId');
            $record = $this->databaseModel->getRecordById((int)$weatherId);

            if ($record !== null) {
                $request = $request->withAttribute('apiResponse', json_decode($record['json']));
                $request = $request->withAttribute('ack', $record['ack']);
            } else {
                $emptyArray = [];
                $request = $request->withAttribute('apiResponse', $emptyArray);
                $request = $request->withAttribute('ack', '');
            }
        } else {
            $ack = date('Y-m-d H:i:s');
            $request = $request->withAttribute('ack', $ack);

            $apiResponse = $request->getAttribute('apiResponse');
            $apiResponse = json_encode($apiResponse);

            $this->databaseModel->saveRecord($ack, $apiResponse);
        }

        $lastRecords = $this->databaseModel->getLastRecords();

        $request = $request->withAttribute('lastRecords', $lastRecords);

        $error = $request->getAttribute('error') ?? $this->databaseModel->getLastError();

        $request = $request->withAttribute('error', $error);

        return $handler->handle($request);
    }
}
