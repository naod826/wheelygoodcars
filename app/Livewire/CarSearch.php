<?php

namespace App\Livewire;

use App\Models\Car;
use Livewire\Component;
use Livewire\WithPagination;

class CarSearch extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $cars = Car::with('tags')
            ->whereNull('sold_at')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('brand', 'like', '%'.$this->search.'%')
                        ->orWhere('model', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $highlightedIds = $cars->getCollection()
            ->pluck('id')
            ->shuffle()
            ->take(min(3, $cars->count()))
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        return view('livewire.car-search', [
            'cars' => $cars,
            'highlightedIds' => $highlightedIds,
        ]);
    }
}
