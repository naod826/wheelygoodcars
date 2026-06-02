<?php

namespace App\Livewire;

use App\Models\Car;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyCarListing extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public function toggleSold(int $carId): void
    {
        $car = Car::where('user_id', Auth::id())->findOrFail($carId);

        $car->update([
            'sold_at' => $car->sold_at ? null : now(),
        ]);
    }

    public function updatePrice(int $carId, $price): void
    {
        $validated = validator(['price' => $price], [
            'price' => 'required|numeric|min:0',
        ])->validate();

        $car = Car::where('user_id', Auth::id())->findOrFail($carId);
        $car->update(['price' => $validated['price']]);
    }

    public function toggleTag(int $carId, int $tagId): void
    {
        $car = Car::where('user_id', Auth::id())->with('tags')->findOrFail($carId);

        if ($car->tags->contains('id', $tagId)) {
            $car->tags()->detach($tagId);
        } else {
            $car->tags()->attach($tagId);
        }
    }

    public function render()
    {
        $cars = Car::with('tags')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.my-car-listing', [
            'cars' => $cars,
            'allTags' => Tag::orderBy('name')->get(),
        ]);
    }
}
