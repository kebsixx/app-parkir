<div class="space-y-5">
    <div>
        <label for="vehicle-search" class="block text-sm font-medium text-ink-soft">Cari kendaraan</label>
        <input
            id="vehicle-search"
            type="search"
            wire:model.live.debounce.300ms="search"
            placeholder="B 1234 XYZ atau nama pemilik"
            class="mt-1.5 block w-full rounded-control border border-line bg-card px-3 py-2.5 text-sm text-ink shadow-sm outline-none transition placeholder:text-muted/70 focus:border-primary focus:ring-2 focus:ring-primary/20"
        >
        <p class="mt-1.5 text-xs text-muted">Ketik minimal 2 karakter. Daftar di bawah otomatis menyesuaikan.</p>
    </div>

    <div class="space-y-2" aria-live="polite">
        @forelse ($vehicles as $vehicle)
            <button
                type="button"
                wire:key="vehicle-{{ $vehicle->id }}"
                wire:click="selectVehicle({{ $vehicle->id }})"
                class="flex w-full items-center justify-between gap-3 rounded-control border px-3 py-3 text-left transition {{ $vehicleId === $vehicle->id ? 'border-primary bg-primary-soft/40 ring-1 ring-primary/20' : 'border-line bg-card hover:bg-surface' }}"
            >
                <span class="flex min-w-0 items-center gap-3">
                    <x-ui.plate :value="$vehicle->plate_number" />
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-medium text-ink">{{ $vehicle->type->name }}</span>
                        <span class="block truncate text-xs text-muted">{{ $vehicle->owner_name ?: 'Pemilik tidak dicatat' }}</span>
                    </span>
                </span>
                <span class="shrink-0 text-xs text-muted">Rp {{ number_format($vehicle->type->hourly_rate, 0, ',', '.') }}/jam</span>
            </button>
        @empty
            <div class="rounded-control border border-dashed border-line bg-surface/60 px-4 py-6 text-center text-sm text-muted">
                Tidak ada kendaraan yang cocok.
                <a href="{{ route('vehicles.create') }}" class="font-medium text-primary hover:underline">Daftarkan kendaraan baru</a>.
            </div>
        @endforelse
    </div>

    @error('vehicleId')
        <p class="text-sm text-danger">{{ $message }}</p>
    @enderror

    @if ($selectedVehicle)
        <div class="rounded-card border border-primary/20 bg-primary-soft/30 p-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-primary-strong">Kendaraan dipilih</p>
                    <div class="mt-1 flex items-center gap-2">
                        <x-ui.plate :value="$selectedVehicle->plate_number" />
                        <span class="text-sm text-ink-soft">{{ $selectedVehicle->type->name }}</span>
                    </div>
                    @if ($selectedVehicle->owner_name)
                        <p class="mt-1 text-xs text-muted">{{ $selectedVehicle->owner_name }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-xs text-muted">Tarif</p>
                    <p class="font-semibold text-ink">Rp {{ number_format($selectedVehicle->type->hourly_rate, 0, ',', '.') }}/jam</p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex items-center justify-between gap-3 border-t border-line pt-4">
        <p class="text-xs text-muted">Durasi dihitung per jam penuh, minimum 1 jam.</p>
        <x-ui.button type="button" wire:click="checkIn" wire:loading.attr="disabled" wire:target="checkIn">
            <span wire:loading.remove wire:target="checkIn">Catat Masuk</span>
            <span wire:loading wire:target="checkIn">Memproses...</span>
        </x-ui.button>
    </div>
</div>
