<?php

namespace App\Repositories\Api\Checkout;

use App\Models\User\Address;
use App\Models\User\User;

class AddressRepository
{
    public function getDefault(User $user): ?Address
    {
        return $user->defaultAddress()
            ->with(['location.parent'])
            ->first();
    }
}
