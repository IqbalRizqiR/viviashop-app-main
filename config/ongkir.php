<?php

return [
    // Binderbyte API for address data (provinces, cities)
    'api_key' => env('RAJAONGKIR_API_KEY'),
    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://api.binderbyte.com'),
    'origin' => env('RAJAONGKIR_ORIGIN', '3517150'), // Cukir, Jombang, Jawa Timur
    
    // RajaOngkir Komerce.id API for shipping cost calculations
    'shipping_api_key' => env('RAJAONGKIR_SHIPPING_API_KEY'),  
    'shipping_base_url' => env('RAJAONGKIR_SHIPPING_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),
    'shipping_origin' => env('RAJAONGKIR_SHIPPING_ORIGIN', '151'), // For shipping cost calculations
];
