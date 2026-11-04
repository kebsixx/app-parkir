@props([
    'label' => null,
    'name',
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name);
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-ink-soft">{{ $label }}</label>
    @endif

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->class([
            'mt-1.5 block w-full rounded-control border bg-card px-3 py-2.5 text-sm text-ink shadow-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20',
            'border-danger' => $errors->has($name),
            'border-line' => ! $errors->has($name),
        ]) }}
    >
        {{ $slot }}
    </select>

    @error($name)
        <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
    @enderror

    @if ($hint)
        <p class="mt-1.5 text-xs text-muted">{{ $hint }}</p>
    @endif
</div>
