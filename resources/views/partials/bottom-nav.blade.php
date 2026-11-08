@auth
    <nav
        x-data="{ moreOpen: false }"
        class="fixed inset-x-0 bottom-0 z-30 border-t border-line bg-card/95 backdrop-blur lg:hidden"
        aria-label="Navigasi cepat"
        style="padding-bottom: env(safe-area-inset-bottom, 0px)"
    >
        <div class="mx-auto grid max-w-lg grid-cols-5">
            @php
                $itemBase = 'flex min-h-[52px] min-w-[44px] flex-col items-center justify-center gap-0.5 px-1 py-2 text-[11px] font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:ring-inset';
                $itemClass = fn (bool $active) => $active ? $itemBase.' text-primary' : $itemBase.' text-muted hover:text-ink';
            @endphp

            <a href="{{ route('dashboard') }}" @class([$itemClass(request()->routeIs('dashboard'))]) @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M4 13h6V4H4v9Zm10 7h6v-9h-6v9ZM4 20h6v-3H4v3Zm10-13h6V4h-6v3Z" stroke-linejoin="round" />
                </svg>
                <span>Beranda</span>
            </a>

            <a href="{{ route('parking.active') }}" @class([$itemClass(request()->routeIs('parking.active', 'parking.checkout', 'parking.success'))]) @if(request()->routeIs('parking.active', 'parking.checkout', 'parking.success')) aria-current="page" @endif>
                <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke-linecap="round" />
                </svg>
                <span>Aktif</span>
            </a>

            <a href="{{ route('parking.check-in') }}" @class([$itemClass(request()->routeIs('parking.check-in*'))]) @if(request()->routeIs('parking.check-in*')) aria-current="page" @endif>
                <span class="flex size-11 items-center justify-center rounded-full bg-primary text-white shadow-sm" aria-hidden="true">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14" stroke-linecap="round" />
                    </svg>
                </span>
                <span class="sr-only">Check-in</span>
            </a>

            <a href="{{ route('vehicles.index') }}" @class([$itemClass(request()->routeIs('vehicles.*'))]) @if(request()->routeIs('vehicles.*')) aria-current="page" @endif>
                <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="m5 16-1-5 2-4h12l2 4-1 5M4 16h16v3H4v-3Z" stroke-linejoin="round" />
                    <path d="M7 19v2M17 19v2" stroke-linecap="round" />
                </svg>
                <span>Kendaraan</span>
            </a>

            <button
                type="button"
                @class([$itemClass(request()->routeIs('vehicle-types.*', 'reports.*'))])
                @click="moreOpen = !moreOpen"
                :aria-expanded="moreOpen.toString()"
                aria-controls="mobile-more-menu"
                aria-haspopup="dialog"
            >
                <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M5 12h.01M12 12h.01M19 12h.01" stroke-linecap="round" stroke-width="2.5" />
                </svg>
                <span>Lainnya</span>
            </button>
        </div>

        <div
            x-show="moreOpen"
            x-transition.opacity
            class="fixed inset-0 z-40 bg-ink/40"
            @click="moreOpen = false"
            x-cloak
            aria-hidden="true"
        ></div>

        <div
            id="mobile-more-menu"
            x-show="moreOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="fixed inset-x-3 bottom-[calc(env(safe-area-inset-bottom,0px)+4.5rem)] z-50 rounded-card border border-line bg-card p-2 shadow-lg"
            role="dialog"
            aria-label="Menu lainnya"
            @keydown.escape.window="moreOpen = false"
            x-cloak
        >
            <div class="border-b border-line px-3 py-2">
                <p class="truncate text-sm font-medium text-ink">{{ auth()->user()->name }}</p>
                <p class="text-xs capitalize text-muted">{{ auth()->user()->role }}</p>
            </div>

            @if (auth()->user()->isAdmin())
                <a href="{{ route('vehicle-types.index') }}" class="flex min-h-[44px] items-center gap-3 rounded-control px-3 py-2.5 text-sm font-medium text-ink-soft transition hover:bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/30" @click="moreOpen = false">
                    Tarif
                </a>
                <a href="{{ route('reports.index') }}" class="flex min-h-[44px] items-center gap-3 rounded-control px-3 py-2.5 text-sm font-medium text-ink-soft transition hover:bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/30" @click="moreOpen = false">
                    Laporan
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-line pt-1">
                @csrf
                <button type="submit" class="flex min-h-[44px] w-full items-center gap-3 rounded-control px-3 py-2.5 text-sm font-medium text-danger transition hover:bg-danger-soft/60 focus:outline-none focus-visible:ring-2 focus-visible:ring-danger/30">
                    Keluar
                </button>
            </form>
        </div>
    </nav>
@endauth
