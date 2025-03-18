<?php

namespace App\UseCases;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use App\Data\NYT\BookData;
use App\Data\NYT\BookListData;
use App\Data\NYT\IsbnData;
use App\Data\NYT\RankData;
use App\Data\NYT\ReviewData;

class NytGetBestsellers
{
    public  function __construct()
    {
    }

    public  function get(array $params): JsonResponse
    {
        $url = config('services.nyt.api_url')
            .config('external-links.new_york_times.best_sellers.history');

        $api_params = array_merge($params, ['api-key' => config('services.nyt.key')]);
        $response = Http::get($url, $api_params);

        if ($response->successful()) {
            return response()->json($response->json(), 200);
        } else {
            return response()->json(['error' => 'Failed to fetch data from NYT API'], 500);
        }
    }

    /**
     * Convert NYT Books API JSON to BookListData
     *
     * @param string $jsonResponse JSON response from NYT API
     * @return BookListData
     */
    public  function convertJsonToDTO(string $jsonResponse): BookListData
    {
        $data = json_decode($jsonResponse, true);

        $books = collect($data['results'])->map(function ($bookData) {

            $isbns = collect($bookData['isbns'])->map(function ($isbnData) {
                return IsbnData::from($isbnData);
            });

            $ranksHistory = collect($bookData['ranks_history'])->map(function ($rankData) {
                return RankData::from($rankData);
            });

            // Convert reviews to collection of ReviewData
            $reviews = collect($bookData['reviews'])->map(function ($reviewData) {
                return ReviewData::from($reviewData);
            });

            // Create and return BookData
            return BookData::fromData($bookData, $isbns, $ranksHistory, $reviews);
        });

        // Create and return BookListData
        return BookListData::fromData($data, $books);
    }
}
