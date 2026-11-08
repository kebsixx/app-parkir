@props([
    'variant' => 'teal', // teal, ink, white
    'markOnly' => false,
])

@php
    $iconBg = match ($variant) {
        'ink' => 'bg-ink text-white',
        'white' => 'bg-white text-primary',
        default => 'bg-primary text-white',
    };
    $wordmark = match ($variant) {
        'ink' => 'text-ink',
        default => 'text-white',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-3']) }} aria-label="ParkirKu">
    <span class="relative grid size-10 shrink-0 place-items-center overflow-hidden rounded-[0.65rem] {{ $iconBg }}" aria-hidden="true">
        <svg viewBox="0 0 256 256" class="size-7" fill="currentColor">
            <path fill-rule="evenodd" d="M64 224 V32 H152 A64 64 0 0 1 152 160 H96 V224 Z M96 72 H144 A28 28 0 0 1 144 128 H96 Z M76 172 H84 V182 H76 Z M76 196 H84 V206 H76 Z"/>
        </svg>
    </span>
    @unless ($markOnly)
        <span class="text-base font-semibold tracking-tight {{ $wordmark }}">ParkirKu</span>
    @endunless
</span>
