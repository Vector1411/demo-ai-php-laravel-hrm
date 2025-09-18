<?php

return [
    'secret' => env('JWT_SECRET'),
    'ttl' => env('JWT_TTL', 15),
    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),
    // ...other config...
];