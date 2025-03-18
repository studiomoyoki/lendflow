<?php

namespace Tests\Unit;

use App\UseCases\NytGetBestsellers;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NytGetBestsellersTest extends TestCase
{
    protected NytGetBestsellers $nytGetBestsellers;
    public function setUp(): void
    {
        parent::setUp();
        $this->nytGetBestsellers = $this->app->make(NytGetBestsellers::class);

    }

    public function test_full_data(): void
    {
        $jsonResponse = file_get_contents(__DIR__ .'/../Resources/nyt-bestsellers.json');

        Http::fake(['*' => Http::response($jsonResponse, 200)]);

        $bookListDTO = $this->nytGetBestsellers->convertJsonToDTO($jsonResponse);

        $this->assertCount(20, $bookListDTO->books);
        $this->assertEquals('OK', $bookListDTO->status);

    }

    public function testGetSuccessfulResponse(): void
    {
        // Mock the HTTP response
        Http::fake([
            '*' => Http::response(['results' => []], 200)
        ]);

        $params = ['some_param' => 'value'];

        $response = $this->nytGetBestsellers->get($params);

        $this->assertEquals(200, $response->status());
    }

    public function testGetFailedResponse(): void
    {
        // Mock the HTTP response
        Http::fake([
            '*' => Http::response('Failed to fetch data from NYT API', 500)
        ]);

        $nytGetBestsellers = new NytGetBestsellers();
        $params = ['some_param' => 'value'];

        $response = $this->nytGetBestsellers->get($params);

        $this->assertEquals(500, $response->status());
        $this->assertEquals("Failed to fetch data from NYT API", $response->getData()->error);
    }
}
