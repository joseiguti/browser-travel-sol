# Prueba desarrollador senior
![Logo del proyecto](https://browsertravelsolutions.com/wp-content/uploads/2022/02/Logo-1.png)
## BrowserTravel

Realizar un microservicio que consulte la humedad de las ciudades Miami, Orlando y New York. Para esto se debe crear un sitio web donde se muestre por medio de un mapa el resultado del microservicio, adicionalmente se debe almacenar en un historial que se pueda consultar a través de un link en la página.

## Requisitos

- PHP 7.4 o superior
- Composer
- SQLite extension para PHP

## Instalación

1. Clona el repositorio o descarga el código fuente.

```
git clone https://github.com/joseiguti/browser-travel-sol.git
```

2. Navega hasta la carpeta del proyecto.

```
cd browser-travel-sol
```

3. Instala las dependencias utilizando Composer.

```
composer install
```

4. Configura la base de datos.

- Abre el archivo `config/autoload/global.php` y modifica la configuración de la base de datos según tus necesidades.

```php
return [
    'database' => 'data/db/weather.db',
];
```

- Configura los parámetros de uso del api de Google Maps y OpenWeatherMap. Estos parámetros serán proporcionados en el email. 

```php
return [

    //...
    
    'whether-service' => [

        //...
        
        'api-key' => '',

    ],

    'google-maps' => [

        //...
        
        'api-key' => ''
    ]

];
```

## Uso del microservicio / frontend

1. Inicia el servidor web de desarrollo.

```
composer serve
```

2. Abre tu navegador web y visita `http://localhost:8080/api/humidity` para ver el objeto de respuesta json.

```json
{
  "ack": "2023-05-28 21:31:46", 
  "data": { 
    "Miami": {
      "lat": 25.7617,
      "lon": -80.1918,
      "humidity": 66
    },
    "Orlando": {
      "lat": 28.5383,
      "lon": -81.3792,
      "humidity": 47
    },
    "Nueva York": {
      "lat": 40.7128,
      "lon": -74.006,
      "humidity": 42
    }
  },
  "historical": [ 
    {
      "id": 3,
      "ack": "2023-05-28 21:31:46"
    },
    {
      "id": 2,
      "ack": "2023-05-28 21:22:24"
    },
    {
      "id": 1,
      "ack": "2023-05-28 21:22:15"
    }
  ],
  "error": "" 
}
```

3. Puedes ver el historico de un resgistro en particular, agregando el parametro query param 'weatherId'. Por ejemplo `http://localhost:8080/api/humidity?weatherId=1`. Se obtendrán los mismos datos pero con el 'ack' respectivo y el dato guardado de humedad.

4. Abre tu navegador web y visita `http://localhost:8080` para ver usar el front.

5. Puedes ver un video de demostración en [este enlace](https://youtube.com/shorts/qL5XCudMTvM).
 

## Contribuciones

Las contribuciones son bienvenidas. Si encuentras algún problema o tienes alguna sugerencia, por favor crea un issue o envía un pull request.

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](https://opensource.org/licenses/MIT).