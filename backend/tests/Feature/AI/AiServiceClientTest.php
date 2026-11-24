<?php

namespace Tests\Feature\AI;

use App\AI\Clients\AiServiceClient;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiServiceClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['ai.service.url' => 'http://ai.test', 'ai.service.token' => 'secret']);
    }

    public function test_it_sends_the_service_token(): void
    {
        Http::fake(['ai.test/v1/predict' => Http::response(['score' => 0.42])]);

        $result = app(AiServiceClient::class)->post('/v1/predict', ['employee_id' => 1]);

        $this->assertSame(['score' => 0.42], $result);
        Http::assertSent(fn (Request $request) => $request->hasHeader('X-Service-Token', 'secret')
            && $request['employee_id'] === 1);
    }

    public function test_health_is_false_when_the_service_is_down(): void
    {
        Http::fake(['ai.test/health' => Http::response(null, 503)]);

        $this->assertFalse(app(AiServiceClient::class)->healthy());
    }

    public function test_errors_are_thrown(): void
    {
        Http::fake(['ai.test/*' => Http::response(['detail' => 'Invalid service token'], 401)]);

        $this->expectException(RequestException::class);

        app(AiServiceClient::class)->post('/v1/predict', []);
    }
}
