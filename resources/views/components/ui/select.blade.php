@props([
    'label' => null,
    'name',
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
    $errorId = $id.'-error';
    $hintId = $id.'-hint';
    $describedBy = collect([$hasError ? $errorId : null, $hint ? $hintId : null])->filter()->implode(' ');
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-ink-soft">{{ $label }}</label>
    @endif

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        @if ($hasError) aria-invalid="true" @endif
        {{ $attributes->class([
            'mt-1.5 block w-full rounded-control border bg-card px-3 py-2.5 text-sm text-ink shadow-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20',
            'border-danger' => $hasError,
            'border-line' => ! $hasError,
        ]) }}
    >
        {{ $slot }}
    </select>

    @error($name)
        <p id="{{ $errorId }}" class="mt-1.5 text-xs text-danger">{{ $message }}</p>
    @enderror

    @if ($hint)
        <p id="{{ $hintId }}" class="mt-1.5 text-xs text-muted">{{ $hint }}</p>
    @endif
</div>
