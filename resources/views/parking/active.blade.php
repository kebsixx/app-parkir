@extends('layouts.app')

@section('title', 'Parkir aktif')
@section('eyebrow', 'Operasional')

@section('actions')
    <x-ui.button :href="route('parking.check-in')" size="sm">Check-in</x-ui.button>
@endsection

@section('content')
    <x-ui.card title="Kendaraan sedang parkir" description="Cari berdasarkan tiket, nomor polisi, atau nama pemilik." padding="p-0">
        <div class="border-b border-line p-4 sm:p-5">
            <form method="GET" action="{{ route('parking.active') }}" class="flex flex-col gap-2 sm:flex-row">
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m20 20-3.5-3.5" stroke-linecap="round" />
                    </svg>
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari tiket, plat, atau pemilik..."
                        class="block w-full rounded-control border border-line bg-card py-2.5 pl-9 pr-3 text-sm text-ink shadow-sm outline-none transition placeholder:text-muted/70 focus:border-primary focus:ring-2 focus:ring-primary/20"
                    >
                </div>
                <x-ui.button type="submit" variant="secondary">Cari</x-ui.button>
                @if ($search !== '')
                    <x-ui.button :href="route('parking.active')" variant="ghost">Reset</x-ui.button>
                @endif
            </form>
        </div>

        @if ($sessions->isEmpty())
            <div class="p-5">
                <x-ui.empty-state
                    title="Belum ada kendaraan parkir"
                    description="Kendaraan yang dicatat masuk akan muncul di sini."
                >
                    <x-ui.button :href="route('parking.check-in')" size="sm">Check-in sekarang</x-ui.button>
                </x-ui.empty-state>
            </div>
        @else
            <div class="hidden overflow-x-auto overscroll-x-contain touch-pan-x touch-pan-y md:block">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="border-b border-line bg-surface/60 text-xs uppercase tracking-wider text-muted">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-medium whitespace-nowrap">Tiket</th>
                            <th scope="col" class="px-5 py-3 font-medium">Kendaraan</th>
                            <th scope="col" class="px-5 py-3 font-medium whitespace-nowrap">Jenis</th>
                            <th scope="col" class="px-5 py-3 font-medium whitespace-nowrap">Masuk</th>
                            <th scope="col" class="px-5 py-3 font-medium whitespace-nowrap">Durasi</th>
                            <th scope="col" class="px-5 py-3 text-right font-medium whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($sessions as $session)
                            <tr class="hover:bg-surface/50">
                                <td class="px-5 py-3 whitespace-nowrap"><x-ui.ticket-code :value="$session->ticket_code" /></td>
                                <td class="px-5 py-3">
                                    <x-ui.plate :value="$session->vehicle->plate_number" />
                                    @if ($session->vehicle->owner_name)
                                        <p class="mt-1 max-w-[14rem] truncate text-xs text-muted">{{ $session->vehicle->owner_name }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-ink-soft">{{ $session->vehicle->type->name }}</td>
                                <td class="px-5 py-3 whitespace-nowrap text-ink-soft">{{ $session->check_in_at->format('H:i') }}</td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <x-ui.badge variant="info">{{ intdiv($session->elapsedMinutes(), 60) }}j {{ $session->elapsedMinutes() % 60 }}m</x-ui.badge>
                                </td>
                                <td class="px-5 py-3 text-right whitespace-nowrap">
                                    <x-ui.button :href="route('parking.checkout', $session)" size="sm">Checkout</x-ui.button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-line md:hidden">
                @foreach ($sessions as $session)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <x-ui.plate :value="$session->vehicle->plate_number" />
                                <p class="mt-1 text-sm text-muted">{{ $session->vehicle->type->name }}{{ $session->vehicle->owner_name ? ' · '.$session->vehicle->owner_name : '' }}</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <x-ui.ticket-code :value="$session->ticket_code" />
                                    <x-ui.badge variant="info">{{ intdiv($session->elapsedMinutes(), 60) }}j {{ $session->elapsedMinutes() % 60 }}m</x-ui.badge>
                                </div>
                            </div>
                            <x-ui.button :href="route('parking.checkout', $session)" size="sm">Checkout</x-ui.button>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($sessions->hasPages())
                <div class="border-t border-line p-4">
                    {{ $sessions->links() }}
                </div>
            @endif
        @endif
    </x-ui.card>
@endsection
