@extends('layouts.app')

@section('content')
    <div class="fullheight">
        <div class="w-100" style="max-width: 720px;">
            <h1 class="h4 mb-3 text-center">Aanbod plaatsen</h1>

            @include('my-cars._progress', ['step' => 1])
            @include('layouts.error')

            <form method="POST" action="{{ route('my-cars.create.step1.post') }}">
                @csrf

                <div class="input-group input-group-lg plate-group shadow-sm">
                    <span class="input-group-text plate-prefix fw-semibold">NL</span>
                    <input
                        id="license_plate"
                        name="license_plate"
                        class="form-control plate-input"
                        placeholder="1-XKD-48"
                        value="{{ old('license_plate') }}"
                        required
                        autofocus
                        aria-label="Kenteken"
                    >
                    <button class="btn plate-go fw-semibold" type="submit">Go!</button>
                </div>
            </form>
        </div>
    </div>
@endsection
