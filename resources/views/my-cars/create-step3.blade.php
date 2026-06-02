@extends('layouts.app')

@section('content')
    <div class="py-4">
        <h1 class="h4 mb-3">Aanbod plaatsen</h1>

        @include('my-cars._progress', ['step' => 3])
        @include('layouts.error')

        <div class="card">
            <div class="card-body">
                <div class="alert alert-secondary mb-3">
                    Kenteken: <strong>{{ $licensePlate }}</strong> — kies tags voor je aanbod.
                </div>

                <form method="POST" action="{{ route('my-cars.store') }}" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="tags[]"
                                        value="{{ $tag->id }}"
                                        id="tag-{{ $tag->id }}"
                                        @checked(in_array($tag->id, old('tags', [])))
                                    >
                                    <label class="form-check-label" for="tag-{{ $tag->id }}">
                                        <span class="badge text-bg-secondary">{{ $tag->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary text-dark" type="submit">Plaats aanbod</button>
                        <a class="btn btn-outline-secondary" href="{{ route('my-cars.create.step2') }}">Terug</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
