@php
    $isEdit = isset($vehicle);
@endphp

<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-ui.input
            label="Nomor polisi"
            name="plate_number"
            :value="old('plate_number', $vehicle->plate_number ?? '')"
            placeholder="B 1234 XYZ"
            hint="Otomatis disimpan dalam huruf besar."
            required
        />
    </div>

    <x-ui.select label="Jenis kendaraan" name="vehicle_type_id" required>
        <option value="">Pilih jenis kendaraan</option>
        @foreach ($vehicleTypes as $type)
            <option value="{{ $type->id }}" @selected((string) old('vehicle_type_id', $vehicle->vehicle_type_id ?? '') === (string) $type->id)>
                {{ $type->name }} · Rp {{ number_format($type->hourly_rate, 0, ',', '.') }}/jam
            </option>
        @endforeach
    </x-ui.select>

    <x-ui.input
        label="Nama pemilik"
        name="owner_name"
        :value="old('owner_name', $vehicle->owner_name ?? '')"
        placeholder="Opsional"
    />

    <x-ui.input
        label="Merek"
        name="brand"
        :value="old('brand', $vehicle->brand ?? '')"
        placeholder="Opsional"
    />

    <x-ui.input
        label="Warna"
        name="color"
        :value="old('color', $vehicle->color ?? '')"
        placeholder="Opsional"
    />
</div>

<div class="mt-6 flex flex-wrap items-center gap-3 border-t border-line pt-5">
    <x-ui.button type="submit">{{ $isEdit ? 'Simpan perubahan' : 'Tambah kendaraan' }}</x-ui.button>
    <x-ui.button :href="route('vehicles.index')" variant="secondary">Batal</x-ui.button>
</div>
