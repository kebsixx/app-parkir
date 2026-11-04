@extends('layouts.app')

@section('title', 'Check-in kendaraan')
@section('eyebrow', 'Transaksi parkir')

@section('actions')
    <x-ui.button :href="route('vehicles.create')" variant="secondary" size="sm">
        + Kendaraan baru
    </x-ui.button>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
        <x-ui.card title="Cari kendaraan" description="Cari berdasarkan nomor polisi atau nama pemilik, lalu catat masuk.">
            <livewire:check-in-form />
        </x-ui.card>

        <aside class="space-y-4">
            <x-ui.card title="Alur check-in" description="Tiga langkah singkat.">
                <ol class="space-y-4 text-sm">
                    <li class="flex gap-3">
                        <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-primary-soft text-xs font-semibold text-primary-strong">1</span>
                        <p class="text-ink-soft">Cari kendaraan yang sudah terdaftar.</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-primary-soft text-xs font-semibold text-primary-strong">2</span>
                        <p class="text-ink-soft">Pilih kendaraan dan cek tarif per jam.</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-primary-soft text-xs font-semibold text-primary-strong">3</span>
                        <p class="text-ink-soft">Tekan <span class="font-medium text-ink">Catat Masuk</span> dan berikan kode tiket.</p>
                    </li>
                </ol>
            </x-ui.card>

            <div class="rounded-card border border-accent/25 bg-accent-soft p-4 text-sm leading-relaxed text-ink-soft">
                <p class="font-medium text-ink">Kendaraan belum terdaftar?</p>
                <p class="mt-1">Daftarkan dulu lewat menu Kendaraan, lalu kembali ke halaman ini.</p>
            </div>
        </aside>
    </div>
@endsection
