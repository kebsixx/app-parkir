@extends('layouts.app')

@section('title', 'Kendaraan')
@section('eyebrow', 'Master data')

@section('actions')
    <x-ui.button :href="route('vehicles.create')" size="sm">+ Tambah kendaraan</x-ui.button>
@endsection

@section('content')
    <x-ui.card title="Daftar kendaraan" description="Data kendaraan yang bisa dicatat masuk." padding="p-0">
        <div class="border-b border-line p-4 sm:p-5">
            <form method="GET" action="{{ route('vehicles.index') }}" class="flex flex-col gap-2 sm:flex-row">
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m20 20-3.5-3.5" stroke-linecap="round" />
                    </svg>
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nomor polisi atau pemilik..."
                        class="block w-full rounded-control border border-line bg-card py-2.5 pl-9 pr-3 text-sm text-ink shadow-sm outline-none transition placeholder:text-muted/70 focus:border-primary focus:ring-2 focus:ring-primary/20"
                    >
                </div>
                <x-ui.button type="submit" variant="secondary">Cari</x-ui.button>
                @if ($search !== '')
                    <x-ui.button :href="route('vehicles.index')" variant="ghost">Reset</x-ui.button>
                @endif
            </form>
        </div>

        @if ($vehicles->isEmpty())
            <div class="p-5">
                <x-ui.empty-state title="Belum ada kendaraan" description="Tambahkan kendaraan pertama untuk mulai mencatat parkir.">
                    <x-ui.button :href="route('vehicles.create')" size="sm">Tambah kendaraan</x-ui.button>
                </x-ui.empty-state>
            </div>
        @else
            <div class="hidden overflow-x-auto overscroll-x-contain touch-pan-x touch-pan-y md:block">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="border-b border-line bg-surface/60 text-xs uppercase tracking-wider text-muted">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-medium whitespace-nowrap">Nomor polisi</th>
                            <th scope="col" class="px-5 py-3 font-medium whitespace-nowrap">Jenis</th>
                            <th scope="col" class="px-5 py-3 font-medium">Pemilik</th>
                            <th scope="col" class="px-5 py-3 font-medium whitespace-nowrap">Merek / warna</th>
                            <th scope="col" class="px-5 py-3 text-right font-medium whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($vehicles as $vehicle)
                            <tr class="hover:bg-surface/50">
                                <td class="px-5 py-3 whitespace-nowrap"><x-ui.plate :value="$vehicle->plate_number" /></td>
                                <td class="px-5 py-3 whitespace-nowrap text-ink-soft">{{ $vehicle->type->name }}</td>
                                <td class="px-5 py-3 text-ink-soft"><span class="block max-w-[14rem] truncate">{{ $vehicle->owner_name ?: '-' }}</span></td>
                                <td class="px-5 py-3 whitespace-nowrap text-muted">{{ trim(($vehicle->brand ?: '-').' / '.($vehicle->color ?: '-')) }}</td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-ui.button :href="route('vehicles.show', $vehicle)" variant="ghost" size="sm">Detail</x-ui.button>
                                        <x-ui.button :href="route('vehicles.edit', $vehicle)" variant="secondary" size="sm">Edit</x-ui.button>
                                        <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Hapus kendaraan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button type="submit" variant="danger" size="sm">Hapus</x-ui.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-line md:hidden">
                @foreach ($vehicles as $vehicle)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <x-ui.plate :value="$vehicle->plate_number" />
                                <p class="mt-1 text-sm text-muted">{{ $vehicle->type->name }}{{ $vehicle->owner_name ? ' · '.$vehicle->owner_name : '' }}</p>
                                <p class="mt-0.5 text-xs text-muted">{{ trim(($vehicle->brand ?: '-').' / '.($vehicle->color ?: '-')) }}</p>
                            </div>
                            <div class="flex shrink-0 flex-col items-end gap-1.5">
                                <x-ui.button :href="route('vehicles.edit', $vehicle)" variant="secondary" size="sm">Edit</x-ui.button>
                                <x-ui.button :href="route('vehicles.show', $vehicle)" variant="ghost" size="sm">Detail</x-ui.button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($vehicles->hasPages())
                <div class="border-t border-line p-4">{{ $vehicles->links() }}</div>
            @endif
        @endif
    </x-ui.card>
@endsection
