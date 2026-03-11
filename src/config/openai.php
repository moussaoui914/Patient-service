<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the OpenAI API client.
    | You can set your API key and base URL here.
    |
    */

    'api_key' => env('OPENAI_API_KEY'),
    
    'base_url' => env('OPENAI_BASE_URL', 'https://api.groq.com/openai/v1'),
    
    'curl' => [
        CURLOPT_CAINFO => env('OPENAI_CURL_CA_BUNDLE', '/etc/ssl/certs/ca-certificates.crt'),
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ],
    
    'client' => [
        'verify' => env('OPENAI_CURL_CA_BUNDLE', '/etc/ssl/certs/ca-certificates.crt'),
        'timeout' => 60,
    ],
];
