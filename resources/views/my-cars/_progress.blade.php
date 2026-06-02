@props(['step'])

@php
    $labels = ['Kenteken', 'Gegevens', 'Tags'];
@endphp

<div class="mb-4">
    <div class="d-flex justify-content-between small mb-2">
        @foreach($labels as $index => $label)
            @php $stepNumber = $index + 1; @endphp
            <span class="{{ $stepNumber <= $step ? 'text-primary fw-semibold' : 'text-secondary' }}">
                {{ $label }}
            </span>
        @endforeach
    </div>
    <div class="progress progress-steps" style="height: 8px;">
        <div
            class="progress-bar bg-primary"
            role="progressbar"
            style="width: {{ ($step / 3) * 100 }}%;"
            aria-valuenow="{{ $step }}"
            aria-valuemin="0"
            aria-valuemax="3"
        ></div>
    </div>
    <p class="text-secondary small mt-2 mb-0">Stap {{ $step }} van 3</p>
</div>
