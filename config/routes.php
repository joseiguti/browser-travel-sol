<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;
use App\Handler\HumidityHandler;
use App\Handler\OpenWeatherMapHandler;
use App\Database\Database;

/**
 * Configuración de rutas de laminas-router.
 *
 * @see https://docs.laminas.dev/laminas-router/
 *
 * Configura las rutas con un solo método de solicitud:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * O con múltiples métodos de solicitud:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * O manejando todos los métodos de solicitud:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * o:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {

    # Ruta del mapa
    $app->get('/', App\Handler\HomePageHandler::class, 'home');

    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');

    # Microservicio requerido
    $app->get('/api/humidity',
        [
            OpenWeatherMapHandler::class, # Capa de llamado a api externa
            Database::class, # Capa de almacenado en la bd u obtencion de resultados
            HumidityHandler::class # Capa final para mostrar los datos
        ], 'api.humidity');

};


