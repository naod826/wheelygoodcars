@extends('layouts.app')

@section('content')
    <div class="py-4">
        <p class="mb-2"><a href="{{ route('cars.index') }}" class="text-decoration-none">← Terug naar alle auto's</a></p>

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
            <div>
                <h1 class="h4 mb-1">{{ $car->brand }} {{ $car->model }}</h1>
                <p class="text-secondary mb-0">{{ $car->license_plate }}</p>
            </div>
            <div class="text-end">
                <div class="h5 mb-0 text-primary">€ {{ number_format((float) $car->price, 2, ',', '.') }}</div>
                @if($car->sold_at)
                    <span class="badge text-bg-secondary mt-1">Verkocht</span>
                @else
                    <span class="badge text-bg-success mt-1">Te koop</span>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Kilometerstand</dt>
                    <dd class="col-sm-8">{{ number_format((int) $car->mileage, 0, ',', '.') }} km</dd>

                    <dt class="col-sm-4">Bouwjaar</dt>
                    <dd class="col-sm-8">{{ $car->production_year ?? '—' }}</dd>

                    <dt class="col-sm-4">Kleur</dt>
                    <dd class="col-sm-8">{{ $car->color ?? '—' }}</dd>

                    <dt class="col-sm-4">Stoelen / deuren</dt>
                    <dd class="col-sm-8">{{ $car->seats ?? '—' }} / {{ $car->doors ?? '—' }}</dd>

                    <dt class="col-sm-4">Gewicht</dt>
                    <dd class="col-sm-8">
                        @if($car->weight)
                            {{ number_format((int) $car->weight, 0, ',', '.') }} kg
                        @else
                            —
                        @endif
                    </dd>

                    <dt class="col-sm-4">Bekeken</dt>
                    <dd class="col-sm-8">{{ number_format((int) $car->views, 0, ',', '.') }} keer</dd>

                    @if($car->tags->isNotEmpty())
                        <dt class="col-sm-4">Tags</dt>
                        <dd class="col-sm-8">
                            @foreach($car->tags as $tag)
                                <span class="badge text-bg-secondary me-1">{{ $tag->name }}</span>
                            @endforeach
                        </dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    @unless($car->sold_at)
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="viewsToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="8000">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>{{ $car->views }}</strong> klanten bekeken deze auto vandaag.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Sluiten"></button>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(function () {
                    const el = document.getElementById('viewsToast');
                    if (el && typeof bootstrap !== 'undefined') {
                        bootstrap.Toast.getOrCreateInstance(el).show();
                    }
                }, 10000);
            });
        </script>
        @endpush
    @endunless
@endsection
