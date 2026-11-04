<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-surface text-ink">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex">
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 z-30 bg-ink/40 lg:hidden"
            @click="sidebarOpen = false"
            x-cloak
        ></div>

        <aside
            x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-40 w-64 transform bg-ink transition-transform duration-200 lg:static lg:translate-x-0"
        >
            @include('partials.sidebar')
        </aside>

        <div class="flex min-h-screen flex-1 flex-col lg:min-w-0">
            <header class="sticky top-0 z-20 border-b border-line bg-card/95 backdrop-blur">
                <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                    <button
                        type="button"
                        class="inline-flex size-10 items-center justify-center rounded-control border border-line text-ink-soft lg:hidden"
                        @click="sidebarOpen = true"
                        aria-label="Buka menu"
                    >
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" />
                        </svg>
                    </button>

                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium uppercase tracking-wider text-muted">@yield('eyebrow', 'Operasional parkir')</p>
                        <h1 class="truncate text-lg font-semibold text-ink">@yield('title', 'Dashboard')</h1>
                    </div>

                    @yield('actions')
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @include('partials.alert')
                @yield('content')
            </main>

            <footer class="border-t border-line px-4 py-4 text-xs text-muted sm:px-6 lg:px-8">
                {{ config('app.name') }} · Sistem parkir kendaraan
            </footer>
        </div>
    </div>

    @livewireScripts
</body>
</html>
