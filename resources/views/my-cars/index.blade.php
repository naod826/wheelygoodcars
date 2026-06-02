@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h1 class="h4 m-0">Mijn aanbod</h1>
            <a href="{{ route('my-cars.create.step1') }}" class="btn btn-primary text-dark">Auto toevoegen</a>
        </div>

        <livewire:my-car-listing />
    </div>
@endsection
