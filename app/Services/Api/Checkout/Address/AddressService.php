<?php

namespace App\Services\Api\Checkout\Address;

use App\Models\User\Address;
use App\Models\User\User;
use App\Repositories\Api\Checkout\AddressRepository;

class AddressService
{
    public function __construct(private AddressRepository $addressRepository)
    {}

    public function getDefault(User $user): ?Address
    {
        return $this->addressRepository->getDefault($user);
    }
}
