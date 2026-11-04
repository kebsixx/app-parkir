@props(['value'])

<span {{ $attributes->merge(['class' => 'font-mono text-xs font-medium tracking-wide text-muted']) }}>
    {{ $value }}
</span>
