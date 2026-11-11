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
    public function test_guest_cannot_get_addresses()
    {
        $this->json('GET', '/api/addresses')
            ->assertUnauthorized();
    }

    /** @test */
    public function test_guest_cannot_create_address()
    {
        $this->json('POST', '/api/addresses', [
            'street'      => 'خیابان انقلاب',
            'postal_code' => '1234567890',
        ])->assertUnauthorized();
    }

    /** @test */
    public function test_cannot_create_address_without_required_fields()
    {
        $this->actingAs($this->user, 'api')
            ->json('POST', '/api/addresses', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['street', 'postal_code']);
    }

    /** @test */
    public function test_creates_address_for_authenticated_user()
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

        $this->actingAs($this->user, 'api')
            ->json('POST', '/api/addresses', [
                'label'       => 'خانه',
                'phone'       => '09123456789',
                'street'      => 'خیابان انقلاب',
                'postal_code' => '1234567890',
                'location_id' => $city->id,
                'is_default'  => true,
            ])
            ->assertCreated()
            ->assertJsonPath('address.label', 'خانه')
            ->assertJsonPath('address.street', 'خیابان انقلاب')
            ->assertJsonPath('address.postal_code', '1234567890')
            ->assertJsonPath('address.phone', '09123456789')
            ->assertJsonPath('address.is_default', true)
            ->assertJsonPath('address.location.id', $city->id)
            ->assertJsonPath('address.location.parent.id', $province->id);

        $this->assertDatabaseHas('addresses', [
            'user_id'     => $this->user->id,
            'location_id' => $city->id,
            'street'      => 'خیابان انقلاب',
            'postal_code' => '1234567890',
        ]);
    }

    /** @test */
    public function test_first_address_is_saved_as_default()
    {
        $this->actingAs($this->user, 'api')
            ->json('POST', '/api/addresses', [
                'street'      => 'خیابان انقلاب',
                'postal_code' => '1234567890',
                'is_default'  => false,
            ])
            ->assertCreated()
            ->assertJsonPath('address.is_default', true);
    }

    /** @test */
    public function test_new_default_address_unsets_previous_default_address()
    {
        $previousDefault = Address::factory()->create([
            'user_id'    => $this->user->id,
            'is_default' => true,
        ]);

        $this->actingAs($this->user, 'api')
            ->json('POST', '/api/addresses', [
                'street'      => 'خیابان انقلاب',
                'postal_code' => '1234567890',
                'is_default'  => true,
            ])
            ->assertCreated()
            ->assertJsonPath('address.is_default', true);

        $this->assertFalse($previousDefault->fresh()->is_default);
        $this->assertEquals(1, $this->user->addresses()->where('is_default', true)->count());
    }

    /** @test */
    public function test_returns_empty_addresses_list_when_user_has_no_address()
    {
        $this->actingAs($this->user, 'api')
            ->json('GET', '/api/addresses')
            ->assertOk()
            ->assertJson(['addresses' => []]);
    }

    /** @test */
    public function test_returns_only_user_addresses_with_default_first()
    {
        $otherUser = User::factory()->create();

        $regularAddress = Address::factory()->create([
            'user_id'    => $this->user->id,
            'is_default' => false,
        ]);

        $defaultAddress = Address::factory()->create([
            'user_id'    => $this->user->id,
            'is_default' => true,
        ]);

        Address::factory()->create([
            'user_id'    => $otherUser->id,
            'is_default' => true,
        ]);

        $this->actingAs($this->user, 'api')
            ->json('GET', '/api/addresses')
            ->assertOk()
            ->assertJsonCount(2, 'addresses')
            ->assertJsonPath('addresses.0.id', $defaultAddress->id)
            ->assertJsonPath('addresses.0.is_default', true)
            ->assertJsonPath('addresses.1.id', $regularAddress->id);
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
