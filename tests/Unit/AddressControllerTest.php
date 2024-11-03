<?php

namespace Tests\Unit;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\Street;
use App\Models\Ward;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_view_with_address_data()
    {
        // Create data
        $country = Country::create(['name' => 'VN']);
        $city = City::create(['name' => 'TP HCM', 'country_id' => $country->id]);
        $district = District::create(['name' => 'Q11', 'city_id' => $city->id]);
        $ward = Ward::create(['name' => 'P15', 'district_id' => $district->id]);
        $street = Street::create(['name' => 'Le Dai Hanh', 'ward_id' => $ward->id]);

        // Send a request to the index method
        $response = $this->get('/index?country=' . $country->id . '&city=' . $city->id . '&district=' . $district->id . '&ward=' . $ward->id . '&street=' . $street->id);

        // Check response is successful
        $response->assertStatus(200);
        $response->assertViewHas('countries');
        $response->assertViewHas('addressString', 'Le Dai Hanh, P15, Q11, TP HCM, VN.');
    }

    public function test_storeAddress_creates_new_address()
    {
        // Create data
        $data = [
            'country_name' => 'VN',
            'city_name' => 'Ha Noi',
            'district_name' => 'Q. Hai Ba Trung',
            'ward_name' => 'P. Minh Khai',
            'street_name' => 'Tam Trinh',
        ];

        // Send a POST request to the storeAddress method
        $response = $this->postJSON('/index/create', $data);

        // Check if the address was created and the response is correct
        $this->assertDatabaseHas('countries', ['name' => 'VN']);
        $this->assertDatabaseHas('cities', ['name' => 'Ha Noi']);
        $this->assertDatabaseHas('districts', ['name' => 'Q. Hai Ba Trung']);
        $this->assertDatabaseHas('wards', ['name' => 'P. Minh Khai']);
        $this->assertDatabaseHas('streets', ['name' => 'Tam Trinh']);
        $response->assertRedirect('/index');
        $response->assertSessionHas('success', 'Address created successfully');
    }
}
