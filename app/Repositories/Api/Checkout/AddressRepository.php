<?php

namespace App\Repositories\Api\Checkout;

use App\Models\User\Address;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Collection;

class AddressRepository
{
    public function getDefault(User $user): ?Address
    {
        return $user->defaultAddress()
            ->with(['location.parent'])
            ->first();
    }

    public function getAll(User $user): Collection
    {
        return $user->addresses()
            ->with(['location.parent'])
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->get();
    }
}
