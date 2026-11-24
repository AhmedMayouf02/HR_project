<?php

return [

    /*
    | Python AI service (../ai-service): your own trained models.
    */
    'service' => [
        'url' => env('AI_SERVICE_URL', 'http://127.0.0.1:8001'),
        'token' => env('AI_SERVICE_TOKEN'),
        'timeout' => (int) env('AI_SERVICE_TIMEOUT', 30),
    ],

    /*
    | External AI provider (LLM API) settings go here once one is chosen,
    | e.g. 'provider' => ['api_key' => env('AI_PROVIDER_API_KEY'), 'model' => ...].
    */

];
