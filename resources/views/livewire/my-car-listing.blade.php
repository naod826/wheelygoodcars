<div>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 my-cars-table">
                <thead class="table-light">
                    <tr>
                        <th>Auto</th>
                        <th>Kenteken</th>
                        <th class="text-end">Prijs</th>
                        <th class="text-end">Km</th>
                        <th class="text-end">Views</th>
                        <th>Tags</th>
                        <th>Status</th>
                        <th class="text-end">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cars as $car)
                        <tr wire:key="car-{{ $car->id }}">
                            <td class="fw-semibold text-nowrap">{{ $car->brand }} {{ $car->model }}</td>
                            <td class="text-secondary">{{ $car->license_plate }}</td>
                            <td class="text-end">
                                <div class="input-group input-group-sm my-car-price-input ms-auto">
                                    <span class="input-group-text">€</span>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="form-control text-end"
                                        value="{{ number_format((float) $car->price, 2, '.', '') }}"
                                        wire:change="updatePrice({{ $car->id }}, $event.target.value)"
                                        aria-label="Vraagprijs"
                                    >
                                </div>
                            </td>
                            <td class="text-end text-nowrap">{{ number_format((int) $car->mileage, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format((int) $car->views, 0, ',', '.') }}</td>
                            <td>
                                <div class="my-car-tags-cell">
                                    <div class="my-car-tags-display">
                                        @forelse($car->tags as $tag)
                                            <span class="badge text-bg-secondary">{{ $tag->name }}</span>
                                        @empty
                                            <span class="text-secondary small">—</span>
                                        @endforelse
                                    </div>
                                    <div class="dropdown">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside"
                                        >
                                            Tags
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end p-2 my-car-tags-menu">
                                            @foreach($allTags as $tag)
                                                <button
                                                    type="button"
                                                    class="dropdown-item rounded small {{ $car->tags->contains('id', $tag->id) ? 'active' : '' }}"
                                                    wire:click="toggleTag({{ $car->id }}, {{ $tag->id }})"
                                                >
                                                    {{ $tag->name }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($car->sold_at)
                                    <span class="badge text-bg-secondary">Verkocht</span>
                                @else
                                    <span class="badge text-bg-success">Te koop</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1 justify-content-end my-car-actions">
                                    @if($car->sold_at)
                                        <button type="button" class="btn btn-sm btn-outline-success" wire:click="toggleSold({{ $car->id }})">Te koop</button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="toggleSold({{ $car->id }})">Verkocht</button>
                                    @endif
                                    <a href="{{ route('cars.show', $car->id) }}" class="btn btn-sm btn-outline-primary">Details</a>
                                    <form method="POST" action="{{ route('my-cars.destroy', $car) }}" class="d-inline" onsubmit="return confirm('Verwijderen?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Verwijderen</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-secondary py-5">Nog geen auto's. Voeg je eerste auto toe.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $cars->links() }}</div>
</div>
