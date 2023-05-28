<?php

declare(strict_types=1);

namespace App\Handler;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Http\Request;
use Laminas\Http\Client;

class OpenWeatherMapHandler implements MiddlewareInterface
{
    private array $cities;
    private string $endPoint;
    private string $apiKey;

    /**
     * Constructor de la clase OpenWeatherMapHandler.
     *
     * @param array $config Configuración del handler.
     */
    public function __construct(array $config)
    {
        $this->cities = $config['cities'];
        $this->endPoint = $config['end-point'];
        $this->apiKey = $config['api-key'];
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
        $apiResponse = [];

        $queryParams = $request->getQueryParams();

        if (isset($queryParams['weatherId'])) {
            $request = $request->withAttribute('weatherId', $queryParams['weatherId']);
        } else {
            foreach ($this->cities as $cityName => $cityAtt) {
                $replacements = [
                    '{lat}' => $cityAtt['lat'],
                    '{lon}' => $cityAtt['lon'],
                    '{api_key}' => $this->apiKey
                ];

                $replacedEndpoint = strtr($this->endPoint, $replacements);

                try {
                    $requestAPI = new Request();
                    $requestAPI->setUri($replacedEndpoint);
                    $requestAPI->setMethod('GET');

                    $client = new Client();
                    $response = $client->dispatch($requestAPI);

                    if ($response->getStatusCode() === 200) {
                        $data = json_decode($response->getBody(), true);

                        $apiResponse[$cityName] = [
                            'lat' => $cityAtt['lat'],
                            'lon' => $cityAtt['lon'],
                            'humidity' => $data['current']['humidity'],
                        ];
                    } else {
                        throw new Exception('Se produjo un error en el middleware');
                    }
                } catch (Exception $e) {
                    $request = $request->withAttribute('error', $e->getMessage());
                }
            }

            $request = $request->withAttribute('apiResponse', $apiResponse);
        }

        return $handler->handle($request);
    }
}
