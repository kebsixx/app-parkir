@if (session('success'))
    <div class="mb-5 flex items-start gap-3 rounded-card border border-success/20 bg-success-soft px-4 py-3 text-sm text-success" role="status">
        <svg class="mt-0.5 size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="m5 13 4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <p>{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="mb-5 flex items-start gap-3 rounded-card border border-danger/20 bg-danger-soft px-4 py-3 text-sm text-danger" role="alert">
        <svg class="mt-0.5 size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 8v5M12 17h.01M10.3 4.2 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3L13.7 4.2a2 2 0 0 0-3.4 0Z" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <p>{{ session('error') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-5 rounded-card border border-danger/20 bg-danger-soft px-4 py-3 text-sm text-danger" role="alert">
        <p class="font-medium">Ada data yang perlu diperbaiki.</p>
        <ul class="mt-1 list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
