<?php

namespace Tests\Feature\Controllers\Api;

use App\Models\User\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_returns_locations_with_their_parent_id()
    {
        $province = Location::create([
            'name' => 'تهران',
            'slug' => 'tehran',
            'type' => Location::TYPE_PROVINCE,
        ]);

        $city = Location::create([
            'name'      => 'شهر تهران',
            'slug'      => 'tehran-city',
            'type'      => Location::TYPE_CITY,
            'parent_id' => $province->id,
        ]);

        $this->json('GET', '/api/locations')
            ->assertOk()
            ->assertJsonCount(2, 'locations')
            ->assertJsonPath('locations.0.id', $province->id)
            ->assertJsonPath('locations.0.type', Location::TYPE_PROVINCE)
            ->assertJsonPath('locations.0.parent_id', null)
            ->assertJsonPath('locations.1.id', $city->id)
            ->assertJsonPath('locations.1.type', Location::TYPE_CITY)
            ->assertJsonPath('locations.1.parent_id', $province->id);
    }
}
