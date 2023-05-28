<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;

class OpenWeatherMapFactory
{
    /**
     * Crea una instancia de la clase OpenWeatherMapHandler.
     *
     * @param ContainerInterface $container Interfaz del contenedor de dependencias.
     * @return OpenWeatherMapHandler Instancia de OpenWeatherMapHandler.
     */
    public function __invoke(ContainerInterface $container): OpenWeatherMapHandler
    {
        return new OpenWeatherMapHandler(
            $container->get('config')['whether-service']
        );
    }
}
