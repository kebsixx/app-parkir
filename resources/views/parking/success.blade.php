@extends('layouts.app')

@section('title', $session->payment ? 'Pembayaran berhasil' : 'Check-in berhasil')
@section('eyebrow', 'Transaksi parkir')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="rounded-card border border-line bg-card p-6 text-center shadow-sm sm:p-8">
            <div class="mx-auto flex size-14 items-center justify-center rounded-full {{ $session->payment ? 'bg-success-soft text-success' : 'bg-primary-soft text-primary' }}">
                <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m5 13 4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>

            <h1 class="mt-4 text-2xl font-semibold tracking-tight text-ink">
                {{ $session->payment ? 'Pembayaran berhasil' : 'Kendaraan tercatat masuk' }}
            </h1>
            <p class="mt-2 text-sm text-muted">
                {{ $session->payment ? 'Kendaraan boleh keluar. Simpan bukti pembayaran bila diperlukan.' : 'Berikan kode tiket ini kepada pengendara.' }}
            </p>

            <div class="mt-6 rounded-card border border-line bg-surface p-5">
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-muted">Kode tiket</p>
                <p class="mt-2 font-mono text-2xl font-semibold tracking-widest text-ink sm:text-3xl">{{ $session->ticket_code }}</p>
                <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                    <x-ui.plate :value="$session->vehicle->plate_number" />
                    <span class="text-sm text-muted">{{ $session->vehicle->type->name }}</span>
                </div>
            </div>

            <dl class="mt-5 grid gap-3 text-left text-sm sm:grid-cols-2">
                <div class="rounded-control border border-line px-4 py-3">
                    <dt class="text-xs text-muted">Masuk</dt>
                    <dd class="mt-1 font-medium text-ink">{{ $session->check_in_at->format('d M Y, H:i') }}</dd>
                </div>

                @if ($session->payment)
                    <div class="rounded-control border border-line px-4 py-3">
                        <dt class="text-xs text-muted">Keluar</dt>
                        <dd class="mt-1 font-medium text-ink">{{ $session->check_out_at?->format('d M Y, H:i') }}</dd>
                    </div>
                    <div class="rounded-control border border-line px-4 py-3">
                        <dt class="text-xs text-muted">Durasi</dt>
                        <dd class="mt-1 font-medium text-ink">{{ intdiv($session->duration_minutes, 60) }} jam {{ $session->duration_minutes % 60 }} menit</dd>
                    </div>
                    <div class="rounded-control border border-line px-4 py-3">
                        <dt class="text-xs text-muted">Total</dt>
                        <dd class="mt-1 font-semibold text-ink">Rp {{ number_format($session->payment->amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="rounded-control border border-line px-4 py-3">
                        <dt class="text-xs text-muted">Uang diterima</dt>
                        <dd class="mt-1 font-medium text-ink">Rp {{ number_format($session->payment->paid_amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="rounded-control border border-line px-4 py-3">
                        <dt class="text-xs text-muted">Kembalian</dt>
                        <dd class="mt-1 font-semibold text-success">Rp {{ number_format($session->payment->change_amount, 0, ',', '.') }}</dd>
                    </div>
                @else
                    <div class="rounded-control border border-line px-4 py-3">
                        <dt class="text-xs text-muted">Tarif</dt>
                        <dd class="mt-1 font-medium text-ink">Rp {{ number_format($session->hourly_rate, 0, ',', '.') }}/jam</dd>
                    </div>
                @endif
            </dl>

            <div class="mt-7 flex flex-col gap-2 sm:flex-row sm:justify-center">
                @if ($session->payment)
                    <x-ui.button :href="route('parking.active')">Kembali ke parkir aktif</x-ui.button>
                    <x-ui.button :href="route('parking.check-in')" variant="secondary">Check-in lagi</x-ui.button>
                @else
                    <x-ui.button :href="route('parking.active')">Lihat parkir aktif</x-ui.button>
                    <x-ui.button :href="route('parking.check-in')" variant="secondary">Check-in lagi</x-ui.button>
                @endif
            </div>
        </div>
    </div>
@endsection
