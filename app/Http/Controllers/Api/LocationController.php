<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\User\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LocationController extends Controller
{
    /**
     * Route:: GET:/api/locations
     */
    public function index(): JsonResponse
    {
        return response()->json(
            ['locations' => LocationResource::collection(Location::all())],
            Response::HTTP_OK
        );
    }
}
