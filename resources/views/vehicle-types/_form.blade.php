@php
    $isEdit = isset($vehicleType);
@endphp

<div class="space-y-4">
    <x-ui.input
        label="Nama jenis kendaraan"
        name="name"
        :value="old('name', $vehicleType->name ?? '')"
        placeholder="Contoh: Motor, Mobil, Truk"
        required
    />

    <x-ui.input
        label="Tarif per jam (Rp)"
        name="hourly_rate"
        type="number"
        min="0"
        step="500"
        :value="old('hourly_rate', $vehicleType->hourly_rate ?? '')"
        placeholder="Contoh: 3000"
        hint="Dihitung per jam penuh, minimum 1 jam."
        required
    />
</div>

<div class="mt-6 flex flex-wrap items-center gap-3 border-t border-line pt-5">
    <x-ui.button type="submit">{{ $isEdit ? 'Simpan perubahan' : 'Tambah jenis' }}</x-ui.button>
    <x-ui.button :href="route('vehicle-types.index')" variant="secondary">Batal</x-ui.button>
</div>
