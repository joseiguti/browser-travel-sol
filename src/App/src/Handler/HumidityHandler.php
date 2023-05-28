<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HumidityHandler implements RequestHandlerInterface
{
    private array $configService;

    /**
     * Crea una instancia de la clase HumidityHandler.
     *
     * @param array $config Configuración necesaria para el handler.
     */
    public function __construct(array $config)
    {
        $this->configService = $config;
    }

    /**
     * Maneja la solicitud HTTP y devuelve una respuesta.
     *
     * @param ServerRequestInterface $request Interfaz de la solicitud HTTP recibida.
     * @return ResponseInterface Respuesta HTTP.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $apiResponse = $request->getAttribute('apiResponse');
        $ack = $request->getAttribute('ack');
        $error = $request->getAttribute('error');
        $lastRecords = $request->getAttribute('lastRecords');

        $jsonResponse = [
            'ack' => $ack,
            'data' => $apiResponse,
            'historical' => $lastRecords,
            'error' => $error
        ];

        return new JsonResponse(
            $jsonResponse
        );
    }
}
