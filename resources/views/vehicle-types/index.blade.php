@extends('layouts.app')

@section('title', 'Tarif kendaraan')
@section('eyebrow', 'Pengaturan admin')

@section('actions')
    <x-ui.button :href="route('vehicle-types.create')" size="sm">+ Tambah jenis</x-ui.button>
@endsection

@section('content')
    <x-ui.card title="Daftar jenis kendaraan & tarif" description="Tarif per jam yang berlaku saat kendaraan check-in." padding="p-0">
        @if ($vehicleTypes->isEmpty())
            <div class="p-5">
                <x-ui.empty-state title="Belum ada jenis kendaraan" description="Tambahkan jenis kendaraan pertama.">
                    <x-ui.button :href="route('vehicle-types.create')" size="sm">Tambah jenis</x-ui.button>
                </x-ui.empty-state>
            </div>
        @else
            <div class="overflow-x-auto overscroll-x-contain touch-pan-x touch-pan-y">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="border-b border-line bg-surface/60 text-xs uppercase tracking-wider text-muted">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-medium whitespace-nowrap">Jenis kendaraan</th>
                            <th scope="col" class="px-5 py-3 font-medium whitespace-nowrap">Tarif / jam</th>
                            <th scope="col" class="px-5 py-3 font-medium whitespace-nowrap">Jumlah kendaraan</th>
                            <th scope="col" class="px-5 py-3 text-right font-medium whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($vehicleTypes as $type)
                            <tr class="hover:bg-surface/50">
                                <td class="px-5 py-3 whitespace-nowrap font-medium text-ink">{{ $type->name }}</td>
                                <td class="px-5 py-3 whitespace-nowrap text-ink-soft">Rp {{ number_format($type->hourly_rate, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 whitespace-nowrap text-muted">{{ $type->vehicles_count }} kendaraan</td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-ui.button :href="route('vehicle-types.edit', $type)" variant="secondary" size="sm">Edit</x-ui.button>
                                        <form method="POST" action="{{ route('vehicle-types.destroy', $type) }}" onsubmit="return confirm('Hapus jenis kendaraan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button type="submit" variant="danger" size="sm" :disabled="$type->vehicles_count > 0">
                                                Hapus
                                            </x-ui.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($vehicleTypes->hasPages())
                <div class="border-t border-line p-4">{{ $vehicleTypes->links() }}</div>
            @endif
        @endif
    </x-ui.card>
@endsection
