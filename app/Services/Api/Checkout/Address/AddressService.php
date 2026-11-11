<?php

namespace App\Services\Api\Checkout\Address;

use App\Models\User\Address;
use App\Models\User\User;
use App\Repositories\Api\Checkout\AddressRepository;
use Illuminate\Database\Eloquent\Collection;

class AddressService
{
    public function __construct(private AddressRepository $addressRepository)
    {}

    public function getDefault(User $user): ?Address
    {
        return $this->addressRepository->getDefault($user);
    }

    public function getAll(User $user): Collection
    {
        return $this->addressRepository->getAll($user);
    }

    public function store(User $user, array $attributes): Address
    {
        return $this->addressRepository->store($user, $attributes);
    }
}
