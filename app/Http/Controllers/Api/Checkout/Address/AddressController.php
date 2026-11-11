<?php

namespace App\Http\Controllers\Api\Checkout\Address;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Checkout\StoreAddressRequest;
use App\Http\Resources\AddressResource;
use App\Services\Api\Checkout\Address\AddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function __construct(private AddressService $addressService)
    {}

    /**
     * Route:: GET:/api/addresses
     */
    public function index(): JsonResponse
    {
        $addresses = $this->addressService->getAll(Auth::user());

        return response()->json(
            ['addresses' => AddressResource::collection($addresses)],
            Response::HTTP_OK
        );
    }

    /**
     * Route:: POST:/api/addresses
     */
    public function store(StoreAddressRequest $request): JsonResponse
    {
        $address = $this->addressService->store(Auth::user(), $request->validated());

        return response()->json(
            ['address' => new AddressResource($address)],
            Response::HTTP_CREATED
        );
    }

    /**
     * Route:: GET:/api/addresses/default
     */
    public function defaultAddress(): JsonResponse
    {
        $address = $this->addressService->getDefault(Auth::user());

        return response()->json(
            ['address' => $address ? new AddressResource($address) : null],
            Response::HTTP_OK
        );
    }
}
