<div>
    <div class="py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h1 class="h4 m-0">Alle auto's</h1>
            <span class="text-secondary small">{{ $cars->total() }} auto's beschikbaar</span>
        </div>

        <div class="mb-3 position-relative">
            <input
                type="search"
                class="form-control"
                placeholder="Zoek op merk of model..."
                wire:model.live.debounce.300ms="search"
                aria-label="Zoek op merk of model"
            >
            <div wire:loading wire:target="search" class="position-absolute top-50 end-0 translate-middle-y me-3">
                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
            </div>
        </div>

        @if($cars->isEmpty())
            <div class="alert alert-info m-0">
                @if($search !== '')
                    Geen auto's gevonden voor "{{ $search }}".
                @else
                    Nog geen auto's beschikbaar.
                @endif
            </div>
        @else
            <div class="row g-3 car-grid" wire:key="cars-page-{{ $cars->currentPage() }}-{{ md5($search) }}">
                @foreach($cars as $car)
                    @php $featured = in_array($car->id, $highlightedIds, true); @endphp
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="{{ route('cars.show', $car->id) }}" class="text-decoration-none text-body d-block h-100">
                            <div class="card h-100 car-card {{ $featured ? 'car-card-featured' : '' }}">
                                <div class="card-body">
                                    @if($featured)
                                        <span class="badge text-bg-primary mb-2">Uitgelicht</span>
                                    @endif
                                    <div class="d-flex justify-content-between gap-2">
                                        <div>
                                            <div class="fw-semibold">{{ $car->brand }} {{ $car->model }}</div>
                                            <div class="text-secondary small">{{ $car->license_plate }}</div>
                                        </div>
                                        <div class="fw-semibold text-nowrap">€ {{ number_format((float) $car->price, 2, ',', '.') }}</div>
                                    </div>
                                    <div class="mt-2 small text-secondary">
                                        {{ number_format((int) $car->mileage, 0, ',', '.') }} km
                                        @if($car->production_year) · {{ $car->production_year }} @endif
                                        · {{ number_format((int) $car->views, 0, ',', '.') }} bekeken
                                    </div>
                                    @if($car->tags->isNotEmpty())
                                        <div class="mt-2 d-flex flex-wrap gap-1">
                                            @foreach($car->tags as $tag)
                                                <span class="badge text-bg-secondary">{{ $tag->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="mt-2 small text-primary">Bekijk details →</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-3" wire:key="pagination-{{ $cars->currentPage() }}">{{ $cars->links() }}</div>
    </div>
</div>
