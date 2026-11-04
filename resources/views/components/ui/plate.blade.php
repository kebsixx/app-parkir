@props(['value'])

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md border border-line bg-surface px-2.5 py-1 font-mono text-sm font-semibold tracking-wider text-ink']) }}>
    {{ $value }}
</span>
