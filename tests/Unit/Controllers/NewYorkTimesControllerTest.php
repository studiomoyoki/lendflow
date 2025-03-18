<?php

namespace Tests\Unit\Controllers;

use App\Data\NYT\FilterData;
use App\Http\Controllers\NewYorkTimesController;
use App\UseCases\NytGetBestsellers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Mockery;

class NewYorkTimesControllerTest extends TestCase
{
    protected NytGetBestsellers $nytGetBestsellers;
    public function setUp(): void
    {
        parent::setUp();
        $this->nytGetBestsellersMock = Mockery::mock(NytGetBestsellers::class);
    }

    public function test_get_bestsellers_unauthorized(): void
    {
        $this->nytGetBestsellersMock->shouldReceive('get')
            ->andReturn(new JsonResponse([], 401));

        $controller = new NewYorkTimesController($this->nytGetBestsellersMock);
        $filterData = $this->app->make(FilterData::class);

        $response = $controller->getBestSellers($filterData);

        $this->assertEquals(401, $response->status());
        $this->assertEquals(['error' => 'Unauthorized access to NYT API'], $response->getData(true));
    }

    public function test_get_bestsellers_internal_server_error(): void
    {
        $this->nytGetBestsellersMock->shouldReceive('get')
            ->andReturn(new JsonResponse([], 500));

        $controller = new NewYorkTimesController($this->nytGetBestsellersMock);
        $filterData = $this->app->make(FilterData::class);

        $response = $controller->getBestSellers($filterData);

        $this->assertEquals(500, $response->status());
        $this->assertEquals(['error' => 'Internal server error from NYT API'], $response->getData(true));
    }

    public function test_get_bestsellers_other_error(): void
    {
        $this->nytGetBestsellersMock->shouldReceive('get')
            ->andReturn(new JsonResponse([], 404));

        $controller = new NewYorkTimesController($this->nytGetBestsellersMock);
        $filterData = $this->app->make(FilterData::class);

        $response = $controller->getBestSellers($filterData);

        $this->assertEquals(404, $response->status());
        $this->assertEquals(['error' => 'Failed to fetch data from NYT API'], $response->getData(true));
    }

    public function test_endpoint_data(): void
    {
        $jsonResponse = file_get_contents(__DIR__ .'/../../Resources/nyt-bestsellers.json');

        Http::fake(['*' => Http::response($jsonResponse, 200)]);

        $result = $this->json('GET', '/api/nyt/best-sellers');

        $result->assertStatus(200);
        $this->assertJson($jsonResponse, $result->getContent());
        $this->assertStringContainsString('results', $result->getContent());
    }
}
