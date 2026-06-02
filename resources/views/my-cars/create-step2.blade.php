@extends('layouts.app')

@section('content')
    <div class="py-4">
        <h1 class="h4 mb-3">Aanbod plaatsen</h1>

        @include('my-cars._progress', ['step' => 2])
        @include('layouts.error')

        @if($rdw)
            <div class="alert alert-success">Gegevens opgehaald via RDW. Controleer en vul aan waar nodig.</div>
        @else
            <div class="alert alert-warning">Geen RDW-gegevens gevonden voor dit kenteken. Vul de gegevens handmatig in.</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('my-cars.create.step2.post') }}" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <div class="alert alert-secondary mb-0">
                            Kenteken: <strong>{{ $licensePlate }}</strong>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="brand">Merk</label>
                        <input id="brand" name="brand" class="form-control" value="{{ old('brand', $rdw['brand'] ?? '') }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="model">Model</label>
                        <input id="model" name="model" class="form-control" value="{{ old('model', $rdw['model'] ?? '') }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="price">Vraagprijs (€)</label>
                        <input id="price" name="price" type="number" step="0.01" min="0" class="form-control" value="{{ old('price') }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="mileage">Kilometerstand</label>
                        <input id="mileage" name="mileage" type="number" min="0" class="form-control" value="{{ old('mileage') }}" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label" for="production_year">Bouwjaar</label>
                        <input id="production_year" name="production_year" type="number" min="1900" max="2100" class="form-control" value="{{ old('production_year', $rdw['production_year'] ?? '') }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="seats">Stoelen</label>
                        <input id="seats" name="seats" type="number" min="1" class="form-control" value="{{ old('seats', $rdw['seats'] ?? '') }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="doors">Deuren</label>
                        <input id="doors" name="doors" type="number" min="1" class="form-control" value="{{ old('doors', $rdw['doors'] ?? '') }}">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="weight">Gewicht (kg)</label>
                        <input id="weight" name="weight" type="number" min="0" class="form-control" value="{{ old('weight', $rdw['weight'] ?? '') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="color">Kleur</label>
                        <input id="color" name="color" class="form-control" value="{{ old('color', $rdw['color'] ?? '') }}">
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary text-dark" type="submit">Volgende: tags</button>
                        <a class="btn btn-outline-secondary" href="{{ route('my-cars.create.step1') }}">Terug</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
