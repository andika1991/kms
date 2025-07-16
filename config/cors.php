<?php
return [
    'paths' => ['api/*', 'storage/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // Untuk testing boleh pakai '*', nanti batasi di production

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
