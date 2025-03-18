<?php

namespace App\Http\Controllers;

use App\Data\NYT\FilterData;
use App\UseCases\NytGetBestsellers;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="NYT Best Sellers API",
 *     version="1.0.0",
 *     description="Endpoint that retrieves data from the NYT Best Sellers API based on certain query parameters."
 * )
 *
 */
class NewYorkTimesController extends Controller
{
    public  function __construct(protected NytGetBestsellers $nytGetBestSellers)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/nyt/best-sellers",
     *     summary="Fetch NYT Best Sellers list",
     *     tags={"Best Sellers"},
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="Author of the book",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *      name="isbn",
     *      in="query",
     *      description="ISBN of the book",
     *      required=false,
     *      @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Title of the book",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          name="offset",
     *          in="query",
     *          description="Pagination offset (must be a multiple of 20)",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              multipleOf=20,
     *              example=0
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A list of NYT Best Sellers",
     *         @OA\JsonContent(
     *             type="object",
     *         )
     *     )
     * )
     */

    public  function getBestSellers(FilterData $filterData): JsonResponse
    {
        $json = $this->nytGetBestSellers->get($filterData->toArray());
        if ($json->status() === 401) {
            return response()->json(['error' => 'Unauthorized access to NYT API'], 401);
        } elseif ($json->status() === 500) {
            return response()->json(['error' => 'Internal server error from NYT API'], 500);
        } elseif ($json->status() !== 200) {
            return response()->json(['error' => 'Failed to fetch data from NYT API'], $json->status());
        }
        $result = $this->nytGetBestSellers->convertJsonToDTO($json->getContent());

        return response()->json($result);
    }

}
