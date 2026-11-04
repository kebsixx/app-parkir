@props(['variant' => 'neutral'])

@php
    $classes = [
        'neutral' => 'bg-surface text-muted ring-1 ring-line',
        'success' => 'bg-success-soft text-success ring-1 ring-success/15',
        'danger' => 'bg-danger-soft text-danger ring-1 ring-danger/15',
        'warning' => 'bg-accent-soft text-ink ring-1 ring-accent/20',
        'info' => 'bg-primary-soft text-primary-strong ring-1 ring-primary/15',
    ][$variant] ?? 'bg-surface text-muted ring-1 ring-line';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {$classes}"]) }}>
    {{ $slot }}
</span>
