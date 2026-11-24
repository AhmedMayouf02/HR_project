<?php

namespace App\AI\Clients;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * HTTP client for the Python AI service (ai-service/), which hosts the
 * project's own trained models.
 */
class AiServiceClient
{
    public function healthy(): bool
    {
        return rescue(fn () => $this->request()->get('/health')->successful(), false, report: false);
    }

    /**
     * Call a model endpoint on the service, e.g. post('/v1/attrition/predict', [...]).
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function post(string $path, array $payload): array
    {
        return $this->request()->post($path, $payload)->throw()->json();
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl(config('ai.service.url'))
            ->acceptJson()
            ->timeout(config('ai.service.timeout'))
            ->withHeaders(array_filter(['X-Service-Token' => config('ai.service.token')]));
    }
}
