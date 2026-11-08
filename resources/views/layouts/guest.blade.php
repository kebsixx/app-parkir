<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') · {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('logo.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon-32.png') }}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{ asset('favicon-16.png') }}" sizes="16x16" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-surface text-ink">
    <main class="grid min-h-screen lg:grid-cols-2">
        <section class="hidden flex-col justify-between bg-ink p-10 text-white lg:flex xl:p-14">
            <div>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-3" aria-label="ParkirKu">
                    <x-ui.logo variant="teal" />
                </a>
            </div>

            <div class="max-w-md">
                <p class="mb-4 font-mono text-xs uppercase tracking-[0.25em] text-accent">Parkir tanpa ribet</p>
                <h1 class="text-4xl font-semibold leading-tight tracking-tight xl:text-5xl">
                    Catat masuk, hitung keluar, selesai.
                </h1>
                <p class="mt-5 text-base leading-relaxed text-slate-300">
                    Satu alur kerja untuk petugas gerbang dan admin. Tarif otomatis, pembayaran tercatat, laporan rapi.
                </p>

                <dl class="mt-10 grid grid-cols-3 gap-4 border-t border-white/10 pt-6">
                    <div>
                        <dt class="text-xs text-slate-400">Check-in</dt>
                        <dd class="mt-1 text-lg font-semibold">&lt; 30 detik</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Tarif</dt>
                        <dd class="mt-1 text-lg font-semibold">Otomatis</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Laporan</dt>
                        <dd class="mt-1 text-lg font-semibold">Harian</dd>
                    </div>
                </dl>
            </div>

            <p class="font-mono text-xs text-slate-500">PKR / 2026</p>
        </section>

        <section class="flex items-center justify-center px-5 py-10 sm:px-8">
            <div class="w-full max-w-md">
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <x-ui.logo variant="ink" />
                </div>

                @yield('content')
            </div>
        </section>
    </main>

    @livewireScripts
</body>
</html>
