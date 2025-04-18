<?php

return [
    'client_id' => env('INSTAGRAM_CLIENT_ID', 'your_client_id'),
    'client_secret' => env('INSTAGRAM_CLIENT_SECRET', 'your_client_secret'),
    'redirect_uri' => env('INSTAGRAM_REDIRECT_URI', 'your_redirect_uri'),
    'access_token' => env('INSTAGRAM_ACCESS_TOKEN', 'your_access_token'),
    'verify_token' => env('INSTAGRAM_VERIFY_TOKEN', 'your_verify_token'),
    'api_url' => 'https://graph.instagram.com/',
];
