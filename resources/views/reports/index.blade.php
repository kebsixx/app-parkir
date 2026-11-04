@extends('layouts.app')

@section('title', 'Laporan transaksi')
@section('eyebrow', 'Laporan admin')

@section('content')
    <div class="space-y-6">
        <x-ui.card title="Filter rentang tanggal" description="Data diambil langsung via internal REST API (P10)." padding="p-5">
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <x-ui.input
                        label="Dari tanggal"
                        name="date_from"
                        type="date"
                        :value="$dateFrom"
                        required
                    />
                </div>
                <div class="flex-1">
                    <x-ui.input
                        label="Sampai tanggal"
                        name="date_to"
                        type="date"
                        :value="$dateTo"
                        required
                    />
                </div>
                <div class="flex gap-2">
                    <x-ui.button type="submit">Filter</x-ui.button>
                    <x-ui.button :href="route('reports.index')" variant="secondary">Hari ini</x-ui.button>
                </div>
            </form>
        </x-ui.card>

        @if ($error)
            <div class="rounded-card border border-accent/25 bg-accent-soft p-4 text-sm text-ink-soft">
                <p class="font-medium text-ink">Catatan Kebutuhan 2 Server (P10):</p>
                <p class="mt-1">{{ $error }}</p>
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <x-ui.stat-card
                label="Total transaksi selesai"
                :value="number_format($summary['total_transactions'] ?? 0, 0, ',', '.')"
                hint="Periode {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} s.d. {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}"
                tone="primary"
            />
            <x-ui.stat-card
                label="Total pendapatan"
                :value="'Rp '.number_format($summary['total_revenue'] ?? 0, 0, ',', '.')"
                hint="Berdasarkan sesi checkout selesai"
                tone="success"
            />
        </div>

        <x-ui.card title="Rincian transaksi selesai" description="Daftar sesi parkir yang sudah lunas dan keluar." padding="p-0">
            @if (empty($sessions))
                <div class="p-5">
                    <x-ui.empty-state
                        title="Tidak ada transaksi"
                        description="Tidak ada data parkir selesai pada rentang tanggal yang dipilih."
                    />
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-line bg-surface/60 text-xs uppercase tracking-wider text-muted">
                            <tr>
                                <th class="px-5 py-3 font-medium">Tiket</th>
                                <th class="px-5 py-3 font-medium">Plat nomor</th>
                                <th class="px-5 py-3 font-medium">Jenis</th>
                                <th class="px-5 py-3 font-medium">Masuk</th>
                                <th class="px-5 py-3 font-medium">Keluar</th>
                                <th class="px-5 py-3 font-medium">Durasi</th>
                                <th class="px-5 py-3 text-right font-medium">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($sessions as $s)
                                <tr class="hover:bg-surface/50">
                                    <td class="px-5 py-3"><x-ui.ticket-code :value="$s['ticket_code']" /></td>
                                    <td class="px-5 py-3">
                                        <x-ui.plate :value="$s['vehicle']['plate_number'] ?? '—'" />
                                    </td>
                                    <td class="px-5 py-3 text-ink-soft">{{ $s['vehicle']['type']['name'] ?? '—' }}</td>
                                    <td class="px-5 py-3 text-ink-soft">
                                        {{ isset($s['check_in_at']) ? \Carbon\Carbon::parse($s['check_in_at'])->format('d/m/Y H:i') : '—' }}
                                    </td>
                                    <td class="px-5 py-3 text-ink-soft">
                                        {{ isset($s['check_out_at']) ? \Carbon\Carbon::parse($s['check_out_at'])->format('d/m/Y H:i') : '—' }}
                                    </td>
                                    <td class="px-5 py-3 text-ink-soft">
                                        @if (isset($s['duration_minutes']))
                                            {{ intdiv($s['duration_minutes'], 60) }}j {{ $s['duration_minutes'] % 60 }}m
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-right font-semibold text-ink">
                                        Rp {{ number_format($s['total_fee'] ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
