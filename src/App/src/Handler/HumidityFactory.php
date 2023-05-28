<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;

class HumidityFactory
{
    /**
     * Crea una instancia de la clase HumidityHandler.
     *
     * @param ContainerInterface $container Interfaz del contenedor de dependencias.
     * @return HumidityHandler Instancia de HumidityHandler.
     */
    public function __invoke(ContainerInterface $container) : HumidityHandler
    {
        return new HumidityHandler(
            $container->get('config')
        );
    }
}
