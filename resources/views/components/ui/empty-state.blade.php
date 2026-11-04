@props([
    'title' => 'Belum ada data',
    'description' => null,
])

<div class="flex flex-col items-center justify-center rounded-card border border-dashed border-line bg-surface/60 px-6 py-12 text-center">
    <div class="mb-3 flex size-11 items-center justify-center rounded-full bg-card text-muted ring-1 ring-line">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
            <path d="M4 7h16M4 12h16M4 17h10" stroke-linecap="round" />
        </svg>
    </div>
    <p class="text-sm font-medium text-ink">{{ $title }}</p>
    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-muted">{{ $description }}</p>
    @endif
    @if (! $slot->isEmpty())
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
