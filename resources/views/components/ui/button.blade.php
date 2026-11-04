@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
    'size' => 'md',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-control font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/30 disabled:cursor-not-allowed disabled:opacity-60';

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-5 py-3 text-sm',
    ];

    $variants = [
        'primary' => 'bg-primary text-white hover:bg-primary-strong',
        'secondary' => 'border border-line bg-card text-ink hover:bg-surface',
        'danger' => 'bg-danger text-white hover:bg-red-800',
        'ghost' => 'text-ink-soft hover:bg-surface',
        'accent' => 'bg-accent text-ink hover:bg-amber-400',
    ];

    $classes = $base.' '.($sizes[$size] ?? $sizes['md']).' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
