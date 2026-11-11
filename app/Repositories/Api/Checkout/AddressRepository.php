<?php

namespace App\Repositories\Api\Checkout;

use App\Models\User\Address;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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

    public function store(User $user, array $attributes): Address
    {
        return DB::transaction(function () use ($user, $attributes) {
            $isDefault = $attributes['is_default'] ?? false;

            if (! $user->addresses()->exists()) {
                $isDefault = true;
            }

            if ($isDefault) {
                $user->addresses()->update(['is_default' => false]);
            }

            $address = $user->addresses()->create([
                'location_id' => $attributes['location_id'] ?? null,
                'street'      => $attributes['street'],
                'postal_code' => $attributes['postal_code'],
                'phone'       => $attributes['phone'] ?? null,
                'label'       => $attributes['label'] ?? null,
                'is_default'  => $isDefault,
            ]);

            return $address->load('location.parent');
        });
    }
}
