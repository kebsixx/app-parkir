@extends('layouts.app')

@section('title', 'Checkout kendaraan')
@section('eyebrow', 'Transaksi parkir')

@section('content')
    <div class="grid gap-6 lg:grid-cols-2">
        <x-ui.card title="Ringkasan sesi" description="Periksa kendaraan dan durasi sebelum menerima pembayaran.">
            <div class="space-y-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <x-ui.plate :value="$session->vehicle->plate_number" class="text-lg" />
                        <p class="mt-2 text-sm text-muted">{{ $session->vehicle->type->name }}{{ $session->vehicle->owner_name ? ' · '.$session->vehicle->owner_name : '' }}</p>
                    </div>
                    <x-ui.badge variant="warning">Masih parkir</x-ui.badge>
                </div>

                <dl class="grid gap-3 rounded-control bg-surface p-4 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs text-muted">Kode tiket</dt>
                        <dd class="mt-1"><x-ui.ticket-code :value="$session->ticket_code" /></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-muted">Tarif per jam</dt>
                        <dd class="mt-1 font-medium text-ink">Rp {{ number_format($session->hourly_rate, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-muted">Masuk</dt>
                        <dd class="mt-1 font-medium text-ink">{{ $session->check_in_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-muted">Durasi berjalan</dt>
                        <dd class="mt-1 font-medium text-ink">
                            {{ intdiv($session->elapsedMinutes(), 60) }} jam {{ $session->elapsedMinutes() % 60 }} menit
                        </dd>
                    </div>
                </dl>

                <div class="flex items-end justify-between gap-4 border-t border-line pt-4">
                    <p class="text-sm text-muted">Total tagihan</p>
                    <p class="text-3xl font-semibold tabular-nums tracking-tight text-ink">Rp {{ number_format($session->calculatedFee(), 0, ',', '.') }}</p>
                </div>
            </div>
        </x-ui.card>

        <livewire:checkout-panel :session="$session" />
    </div>
@endsection
