<?php

namespace Tests\Feature\Controllers\Api\Checkout\Address;

use App\Models\User\Address;
use App\Models\User\Location;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function test_guest_cannot_get_default_address()
    {
        $this->json('GET', '/api/addresses/default')
            ->assertUnauthorized();
    }

    /** @test */
    public function test_returns_null_when_user_has_no_address()
    {
        $this->actingAs($this->user, 'api')
            ->json('GET', '/api/addresses/default')
            ->assertOk()
            ->assertJson(['address' => null]);
    }

    /** @test */
    public function test_returns_default_address_with_its_location()
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

        $address = Address::factory()->create([
            'user_id'     => $this->user->id,
            'location_id' => $city->id,
            'is_default'  => true,
        ]);

        Address::factory()->create([
            'user_id'    => $this->user->id,
            'is_default' => false,
        ]);

        $this->actingAs($this->user, 'api')
            ->json('GET', '/api/addresses/default')
            ->assertOk()
            ->assertJsonPath('address.id', $address->id)
            ->assertJsonPath('address.is_default', true)
            ->assertJsonPath('address.location.id', $city->id)
            ->assertJsonPath('address.location.parent.id', $province->id);
    }
}
