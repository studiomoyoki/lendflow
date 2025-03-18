<?php

namespace Tests\Feature;

use App\Data\NYT\FilterData;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
class NYTConnectionTest extends TestCase
{
    private bool $testExternal;
    private string $nytApiUrl;
    private string $nytApiKey;
    private string $nytBestSellersHistoryEndpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testExternal = config('app.features.test_external');

        if ($this->testExternal === false) {
            $this->markTestSkipped('External connections are disabled');
        }

        $this->nytApiUrl = config('services.nyt.api_url');
        $this->nytApiKey = config('services.nyt.key');
        $this->nytBestSellersHistoryEndpoint = config('external-links.new_york_times.best_sellers.history');
    }

    public function test_nyt_api_real_connection(): void
    {
        $url = $this->nytApiUrl.$this->nytBestSellersHistoryEndpoint;

        $api_params = array_merge(FilterData::empty(), ['api-key' => $this->nytApiKey]);
        $response = Http::get($url, $api_params);

        $this->assertTrue($response->successful());
        $this->assertJson($response->body());
        $this->assertArrayHasKey('results', $response->json());
    }

    public function test_nyt_api_real_connection_but_wrong_key(): void
    {
        $url = $this->nytApiUrl.$this->nytBestSellersHistoryEndpoint;

        $api_params = array_merge(FilterData::empty(), ['api-key' => $this->nytApiKey.'wrong']);
        $response = Http::get($url, $api_params);

        $this->assertFalse($response->successful());
        $this->assertJson(401, $response->status());
    }
}
