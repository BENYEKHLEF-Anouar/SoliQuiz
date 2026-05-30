<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost', 'http://localhost:3000', 'http://127.0.0.1', 'http://127.0.0.1:8000', 'http://127.0.0.1:8001', 'http://localhost:8001'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];