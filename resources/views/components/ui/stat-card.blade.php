@props([
    'label',
    'value',
    'hint' => null,
    'tone' => 'default',
])

@php
    $tones = [
        'default' => 'text-ink',
        'primary' => 'text-primary',
        'accent' => 'text-amber-600',
        'success' => 'text-success',
    ];
@endphp

<div class="rounded-card border border-line bg-card p-5 shadow-sm">
    <p class="text-xs font-medium uppercase tracking-wider text-muted">{{ $label }}</p>
    <p class="mt-2 text-3xl font-semibold tabular-nums tracking-tight {{ $tones[$tone] ?? $tones['default'] }}">{{ $value }}</p>
    @if ($hint)
        <p class="mt-1 text-xs text-muted">{{ $hint }}</p>
    @endif
</div>
