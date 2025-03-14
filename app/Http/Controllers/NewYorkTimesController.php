<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewYorkTimesController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @OA\Info(
     *     title="New York Times API",
     *     version="1.0.0"
     * )
     *
     * @OA\Server(
     *     url="http://localhost/server/texas",
     *     description="Local server"
     * )
     *
     * @OA\Tag(
     *     name="NewYorkTimes",
     *     description="Operations related to New York Times articles"
     * )
     *
     * @OA\Get(
     *     path="/api/new-york-times",
     *     tags={"NewYorkTimes"},
     *     summary="Get all New York Times articles",
     *    operationId="getNewYorkTimes",
     *    @OA\Response(
     */
    //**

    public function history(Request $request)
    {
        $history = $request->user()->history;
        return response()->json($history);
    }


}
