@extends('layouts.app')

@section('title', 'Detail kendaraan')
@section('eyebrow', 'Master data')

@section('actions')
    <x-ui.button :href="route('vehicles.edit', $vehicle)" size="sm">Edit</x-ui.button>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-[320px_minmax(0,1fr)]">
        <x-ui.card title="Informasi kendaraan">
            <dl class="space-y-4 text-sm">
                <div>
                    <dt class="text-xs text-muted">Nomor polisi</dt>
                    <dd class="mt-1"><x-ui.plate :value="$vehicle->plate_number" class="text-base" /></dd>
                </div>
                <div>
                    <dt class="text-xs text-muted">Jenis</dt>
                    <dd class="mt-1 font-medium text-ink">{{ $vehicle->type->name }} — Rp {{ number_format($vehicle->type->hourly_rate, 0, ',', '.') }}/jam</dd>
                </div>
                <div>
                    <dt class="text-xs text-muted">Pemilik</dt>
                    <dd class="mt-1 text-ink-soft">{{ $vehicle->owner_name ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-muted">Merek / warna</dt>
                    <dd class="mt-1 text-ink-soft">{{ trim(($vehicle->brand ?: '—').' / '.($vehicle->color ?: '—')) }}</dd>
                </div>
            </dl>
        </x-ui.card>

        <x-ui.card title="Riwayat parkir" description="Transaksi terbaru untuk kendaraan ini." padding="p-0">
            @if ($vehicle->parkingSessions->isEmpty())
                <div class="p-5">
                    <x-ui.empty-state title="Belum ada riwayat" description="Kendaraan ini belum pernah tercatat parkir." />
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-line bg-surface/60 text-xs uppercase tracking-wider text-muted">
                            <tr>
                                <th class="px-5 py-3 font-medium">Tiket</th>
                                <th class="px-5 py-3 font-medium">Masuk</th>
                                <th class="px-5 py-3 font-medium">Keluar</th>
                                <th class="px-5 py-3 font-medium">Durasi</th>
                                <th class="px-5 py-3 text-right font-medium">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($vehicle->parkingSessions as $session)
                                <tr>
                                    <td class="px-5 py-3"><x-ui.ticket-code :value="$session->ticket_code" /></td>
                                    <td class="px-5 py-3 text-ink-soft">{{ $session->check_in_at->format('d M Y, H:i') }}</td>
                                    <td class="px-5 py-3 text-ink-soft">{{ $session->check_out_at?->format('d M Y, H:i') ?? '—' }}</td>
                                    <td class="px-5 py-3 text-ink-soft">
                                        @if ($session->duration_minutes)
                                            {{ intdiv($session->duration_minutes, 60) }}j {{ $session->duration_minutes % 60 }}m
                                        @else
                                            {{ intdiv($session->elapsedMinutes(), 60) }}j {{ $session->elapsedMinutes() % 60 }}m
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-right font-medium text-ink">
                                        {{ $session->total_fee ? 'Rp '.number_format($session->total_fee, 0, ',', '.') : '—' }}
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
