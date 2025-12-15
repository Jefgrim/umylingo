<?php

use Illuminate\Http\Request;

return [
    'proxies' => env('TRUSTED_PROXIES', ['127.0.0.1', '::1']),
    'headers' => Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_PREFIX,
];
