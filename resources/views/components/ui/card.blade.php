@props([
    'title' => null,
    'description' => null,
    'padding' => 'p-5',
])

<div {{ $attributes->merge(['class' => 'rounded-card border border-line bg-card shadow-sm']) }}>
    @if ($title || isset($actions))
        <div class="flex flex-wrap items-start justify-between gap-3 border-b border-line px-5 py-4">
            <div class="min-w-0">
                @if ($title)
                    <h2 class="text-base font-semibold text-ink">{{ $title }}</h2>
                @endif
                @if ($description)
                    <p class="mt-1 text-sm text-muted">{{ $description }}</p>
                @endif
            </div>

            @isset($actions)
                <div class="shrink-0">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>
</div>
