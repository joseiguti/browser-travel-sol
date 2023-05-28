<?php

declare(strict_types=1);

return [

    'database' => 'data/db/weather.db',

    'whether-service' => [

        'cities' => [

            'Miami' => [
                'lat' => 25.7617,
                'lon' => -80.1918
            ],

            'Orlando' => [
                'lat' => 28.5383,
                'lon' => -81.3792
            ],

            'Nueva York' => [
                'lat' => 40.7128,
                'lon' => -74.0060
            ],

        ],

        'end-point' => 'https://api.openweathermap.org/data/3.0/onecall?lat={lat}&lon={lon}&appid={api_key}',

        'api-key' => '',

    ],

    'google-maps' => [

        'end-point' => 'https://maps.googleapis.com/maps/api/js?key={api_key}&callback=initMap',

        'api-key' => ''
    ]

];