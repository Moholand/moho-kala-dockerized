<?php

namespace App\Http\Controllers\Api\Checkout\Address;

use App\Http\Controllers\Controller;
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
