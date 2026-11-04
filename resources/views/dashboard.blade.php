@extends('layouts.app')

@section('title', 'Dashboard')
@section('eyebrow', 'Ringkasan operasional')

@section('actions')
    <x-ui.button :href="route('parking.check-in')" size="sm">
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 5v14M5 12h14" stroke-linecap="round" />
        </svg>
        Check-in
    </x-ui.button>
@endsection

@section('content')
    <div class="grid gap-4 sm:grid-cols-3">
        <x-ui.stat-card
            label="Kendaraan parkir"
            :value="number_format($stats['parked_count'], 0, ',', '.')"
            hint="Sesi yang masih berjalan"
            tone="primary"
        />
        <x-ui.stat-card
            label="Transaksi hari ini"
            :value="number_format($stats['today_transactions'], 0, ',', '.')"
            hint="Sesi selesai hari ini"
        />
        <x-ui.stat-card
            label="Pendapatan hari ini"
            :value="'Rp '.number_format($stats['today_revenue'], 0, ',', '.')"
            hint="Total pembayaran tunai"
            tone="success"
        />
    </div>

    <div class="mt-6">
        <x-ui.card title="Sesi aktif terbaru" description="Kendaraan yang masih berada di area parkir." padding="p-0">
            <x-slot:actions>
                <x-ui.button :href="route('parking.active')" variant="secondary" size="sm">
                    Lihat semua
                </x-ui.button>
            </x-slot:actions>

            @if ($activeSessions->isEmpty())
                <div class="p-5">
                    <x-ui.empty-state
                        title="Belum ada kendaraan parkir"
                        description="Mulai dengan mencatat kendaraan masuk."
                    >
                        <x-ui.button :href="route('parking.check-in')" size="sm">Check-in sekarang</x-ui.button>
                    </x-ui.empty-state>
                </div>
            @else
                <div class="hidden overflow-x-auto md:block">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-line bg-surface/60 text-xs uppercase tracking-wider text-muted">
                            <tr>
                                <th class="px-5 py-3 font-medium">Tiket</th>
                                <th class="px-5 py-3 font-medium">Kendaraan</th>
                                <th class="px-5 py-3 font-medium">Jenis</th>
                                <th class="px-5 py-3 font-medium">Masuk</th>
                                <th class="px-5 py-3 text-right font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($activeSessions as $session)
                                <tr class="hover:bg-surface/50">
                                    <td class="px-5 py-3">
                                        <x-ui.ticket-code :value="$session->ticket_code" />
                                    </td>
                                    <td class="px-5 py-3">
                                        <x-ui.plate :value="$session->vehicle->plate_number" />
                                    </td>
                                    <td class="px-5 py-3 text-ink-soft">{{ $session->vehicle->type->name }}</td>
                                    <td class="px-5 py-3 text-ink-soft">{{ $session->check_in_at->format('H:i') }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <x-ui.button :href="route('parking.checkout', $session)" size="sm" variant="secondary">
                                            Checkout
                                        </x-ui.button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="divide-y divide-line md:hidden">
                    @foreach ($activeSessions as $session)
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <x-ui.plate :value="$session->vehicle->plate_number" />
                                    <p class="mt-1 text-sm text-muted">{{ $session->vehicle->type->name }} · masuk {{ $session->check_in_at->format('H:i') }}</p>
                                    <x-ui.ticket-code :value="$session->ticket_code" class="mt-2 block" />
                                </div>
                                <x-ui.button :href="route('parking.checkout', $session)" size="sm" variant="secondary">Checkout</x-ui.button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
