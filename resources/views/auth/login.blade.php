@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <div class="rounded-card border border-line bg-card p-6 shadow-sm sm:p-8">
        <div class="mb-6">
            <p class="font-mono text-xs uppercase tracking-[0.2em] text-primary">Area petugas</p>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight text-ink">Masuk ke ParkirKu</h1>
            <p class="mt-2 text-sm leading-relaxed text-muted">Gunakan akun yang diberikan admin untuk mengakses operasional parkir.</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf

            <x-ui.input
                label="Email"
                name="email"
                type="email"
                :value="old('email')"
                placeholder="nama@parkirku.test"
                autocomplete="email"
                autofocus
                required
            />

            <x-ui.input
                label="Password"
                name="password"
                type="password"
                placeholder="Masukkan password"
                autocomplete="current-password"
                required
            />

            <label class="flex items-center gap-2 text-sm text-ink-soft">
                <input type="checkbox" name="remember" value="1" class="size-4 rounded border-line text-primary focus:ring-primary/30">
                Ingat saya
            </label>

            <x-ui.button type="submit" class="w-full" size="lg">
                Masuk
            </x-ui.button>
        </form>

        <div class="mt-6 rounded-control bg-surface px-4 py-3 text-xs leading-relaxed text-muted">
            <p class="font-medium text-ink-soft">Akun demo</p>
            <p class="mt-1">Admin: <span class="font-mono">admin@parkirku.test</span> / <span class="font-mono">password</span></p>
            <p>Petugas: <span class="font-mono">petugas@parkirku.test</span> / <span class="font-mono">password</span></p>
        </div>
    </div>
@endsection
