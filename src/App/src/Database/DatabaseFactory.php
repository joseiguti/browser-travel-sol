<?php

declare(strict_types=1);

namespace App\Database;

use Psr\Container\ContainerInterface;

class DatabaseFactory
{
    /**
     * Crea una instancia de la clase Database.
     *
     * @param ContainerInterface $container Contenedor de dependencias.
     * @return Database Instancia de la clase Database.
     */
    public function __invoke(ContainerInterface $container): Database
    {
        $databaseModel = new DatabaseModel($container->get('config')['database']);

        return new Database(
            $databaseModel
        );
    }
}
