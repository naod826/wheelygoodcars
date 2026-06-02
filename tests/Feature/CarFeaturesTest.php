<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Tag;
use App\Models\User;
use App\Services\RdwService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\CarSearch;
use App\Livewire\MyCarListing;
use Tests\TestCase;

class CarFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function seedMinimalData(): void
    {
        User::factory(5)->create();
        $names = ['Automaat', 'NAP', 'LED', 'Leder', 'Diesel'];
        foreach ($names as $name) {
            Tag::create(['name' => $name]);
        }

        $userId = User::first()->id;
        $tagIds = Tag::pluck('id')->all();

        for ($i = 0; $i < 25; $i++) {
            $car = Car::create([
                'user_id' => $userId,
                'license_plate' => 'AB'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'brand' => $i % 2 === 0 ? 'BMW' : 'Audi',
                'model' => 'Model '.$i,
                'price' => 10000 + $i * 100,
                'mileage' => 50000 + $i * 1000,
                'production_year' => 2020,
            ]);
            $car->tags()->attach(array_slice($tagIds, 0, 2));
        }
    }

    public function test_f2_public_listing_without_login(): void
    {
        $this->seedMinimalData();

        $this->get(route('cars.index'))->assertOk();
        $this->get(route('home'))->assertOk();
        $this->assertGuest();
    }

    public function test_f3_car_detail_page(): void
    {
        $this->seedMinimalData();
        $car = Car::whereNull('sold_at')->first();

        $this->get(route('cars.show', $car->id))
            ->assertOk()
            ->assertSee($car->brand)
            ->assertSee($car->model);
    }

    public function test_f8_pagination_shows_twelve_per_page(): void
    {
        $this->seedMinimalData();

        Livewire::test(CarSearch::class)
            ->assertSee('Model 0')
            ->call('gotoPage', 2)
            ->assertSee('Model 12')
            ->assertDontSee('Model 0');
    }

    public function test_f7_search_filters_by_brand_without_reload(): void
    {
        $this->seedMinimalData();

        Livewire::test(CarSearch::class)
            ->set('search', 'BMW')
            ->assertSee('BMW')
            ->assertDontSee('Audi Model');
    }

    public function test_f12_tags_visible_on_listing(): void
    {
        $this->seedMinimalData();

        Livewire::test(CarSearch::class)
            ->assertSee('Automaat')
            ->assertSee('Diesel');
    }

    public function test_b8_views_increment_on_detail_page(): void
    {
        $this->seedMinimalData();
        $car = Car::first();
        $car->update(['views' => 5]);

        $this->get(route('cars.show', $car->id));

        $this->assertEquals(6, $car->fresh()->views);
    }

    public function test_b8_views_shown_in_my_listing(): void
    {
        $this->seedMinimalData();
        $user = User::first();
        $car = Car::where('user_id', $user->id)->first();
        $car->update(['views' => 42]);

        Livewire::actingAs($user)
            ->test(MyCarListing::class)
            ->assertSee('42');
    }

    public function test_f1_toggle_sold_and_update_price(): void
    {
        $this->seedMinimalData();
        $user = User::first();
        $car = Car::where('user_id', $user->id)->first();

        Livewire::actingAs($user)
            ->test(MyCarListing::class)
            ->call('toggleSold', $car->id);

        $this->assertNotNull($car->fresh()->sold_at);

        Livewire::actingAs($user)
            ->test(MyCarListing::class)
            ->call('updatePrice', $car->id, 15000);

        $this->assertEquals('15000.00', $car->fresh()->price);
    }

    public function test_sold_cars_hidden_from_public_listing(): void
    {
        $this->seedMinimalData();
        $car = Car::first();
        $car->update(['sold_at' => now(), 'brand' => 'VERKOCHTMERK']);

        Livewire::test(CarSearch::class)
            ->assertDontSee('VERKOCHTMERK');
    }

    public function test_multistep_create_flow_f6_f10(): void
    {
        $this->seedMinimalData();
        $user = User::first();

        $response = $this->actingAs($user)
            ->post(route('my-cars.create.step1.post'), ['license_plate' => '1-XKD-48']);

        $response->assertRedirect(route('my-cars.create.step2'));

        $response = $this->actingAs($user)
            ->post(route('my-cars.create.step2.post'), [
                'brand' => 'Test',
                'model' => 'Auto',
                'price' => 5000,
                'mileage' => 100000,
            ]);

        $response->assertRedirect(route('my-cars.create.step3'));

        $tagId = Tag::first()->id;

        $response = $this->actingAs($user)
            ->post(route('my-cars.store'), ['tags' => [$tagId]]);

        $response->assertRedirect(route('my-cars.index'));

        $this->assertDatabaseHas('cars', [
            'license_plate' => '1XKD48',
            'brand' => 'Test',
            'user_id' => $user->id,
        ]);

        $car = Car::where('license_plate', '1XKD48')->first();
        $this->assertTrue($car->tags->contains('id', $tagId));
    }

    public function test_f6_progress_bar_on_create_steps(): void
    {
        $this->seedMinimalData();
        $user = User::first();

        $this->actingAs($user)
            ->get(route('my-cars.create.step1'))
            ->assertOk()
            ->assertSee('Stap 1 van 3');

        $this->actingAs($user)
            ->withSession(['new_car_plate' => 'TEST01'])
            ->get(route('my-cars.create.step2'))
            ->assertOk()
            ->assertSee('Stap 2 van 3');

        $this->actingAs($user)
            ->withSession(['new_car_plate' => 'TEST01', 'new_car_data' => ['brand' => 'X', 'model' => 'Y', 'price' => 1, 'mileage' => 1]])
            ->get(route('my-cars.create.step3'))
            ->assertOk()
            ->assertSee('Stap 3 van 3');
    }

    public function test_f4_toast_on_detail_page(): void
    {
        $this->seedMinimalData();
        $car = Car::whereNull('sold_at')->first();

        $this->get(route('cars.show', $car->id))
            ->assertOk()
            ->assertSee('viewsToast')
            ->assertSee('bekeken deze auto vandaag');
    }

    public function test_f5_featured_cars_on_listing(): void
    {
        $this->seedMinimalData();

        Livewire::test(CarSearch::class)
            ->assertSee('Uitgelicht');
    }

    public function test_b1_rdw_service_returns_data_or_null(): void
    {
        $rdw = app(RdwService::class);
        $result = $rdw->lookup('1XKD48');

        $this->assertTrue($result === null || (is_array($result) && array_key_exists('brand', $result)));
    }
}
