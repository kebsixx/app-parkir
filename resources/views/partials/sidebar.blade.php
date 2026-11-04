<div class="flex h-full flex-col">
    <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="flex size-9 items-center justify-center rounded-control bg-accent text-sm font-bold text-ink">P</span>
            <span class="text-base font-semibold tracking-tight text-white">ParkirKu</span>
        </a>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto p-3" aria-label="Navigasi utama">
        @php
            $navClass = fn (bool $active) => $active
                ? 'bg-white/10 text-white'
                : 'text-slate-300 hover:bg-white/5 hover:text-white';
        @endphp

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-control px-3 py-2.5 text-sm font-medium {{ $navClass(request()->routeIs('dashboard')) }}">
            <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="M4 13h6V4H4v9Zm10 7h6v-9h-6v9ZM4 20h6v-3H4v3Zm10-13h6V4h-6v3Z" stroke-linejoin="round" />
            </svg>
            Dashboard
        </a>

        <a href="{{ route('parking.active') }}" class="flex items-center gap-3 rounded-control px-3 py-2.5 text-sm font-medium {{ $navClass(request()->routeIs('parking.active')) }}">
            <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke-linecap="round" />
            </svg>
            Parkir Aktif
        </a>

        <a href="{{ route('parking.check-in') }}" class="flex items-center gap-3 rounded-control px-3 py-2.5 text-sm font-medium {{ $navClass(request()->routeIs('parking.check-in')) }}">
            <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="M12 5v14M5 12h14" stroke-linecap="round" />
            </svg>
            Check-in
        </a>

        <a href="{{ route('vehicles.index') }}" class="flex items-center gap-3 rounded-control px-3 py-2.5 text-sm font-medium {{ $navClass(request()->routeIs('vehicles.*')) }}">
            <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="m5 16-1-5 2-4h12l2 4-1 5M4 16h16v3H4v-3Z" stroke-linejoin="round" />
                <path d="M7 19v2M17 19v2" stroke-linecap="round" />
            </svg>
            Kendaraan
        </a>

        @if (auth()->user()->isAdmin())
            <div class="px-3 pb-1 pt-4 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">Admin</div>

            <a href="{{ route('vehicle-types.index') }}" class="flex items-center gap-3 rounded-control px-3 py-2.5 text-sm font-medium {{ $navClass(request()->routeIs('vehicle-types.*')) }}">
                <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path d="M4 7h16M4 12h10M4 17h7" stroke-linecap="round" />
                </svg>
                Tarif
            </a>

            <a href="{{ route('reports.index') }}" class="flex items-center gap-3 rounded-control px-3 py-2.5 text-sm font-medium {{ $navClass(request()->routeIs('reports.*')) }}">
                <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path d="M5 20V10M12 20V4M19 20v-7" stroke-linecap="round" />
                </svg>
                Laporan
            </a>
        @endif
    </nav>

    <div class="border-t border-white/10 p-4">
        <div class="mb-3 min-w-0">
            <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
            <p class="mt-0.5 text-xs capitalize text-slate-400">{{ auth()->user()->role }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-control border border-white/15 px-3 py-2 text-xs font-medium text-slate-300 transition hover:border-white/30 hover:text-white">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path d="M15 12H4m4-4-4 4 4 4M14 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Keluar
            </button>
        </form>
    </div>
</div>
