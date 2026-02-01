@php
    $active = $active ?? null;
@endphp

<header id="site-header" class="sticky top-0 z-50">
    {{-- background --}}
    <div class="absolute inset-0 bg-gradient-to-r from-blue-700 via-indigo-700 to-sky-600"></div>
    <div class="absolute inset-0 bg-black/10 backdrop-blur-md"></div>

    <div class="relative max-w-7xl mx-auto px-6">
        <div class="header-inner h-20 flex items-center justify-between text-white transition-all duration-300">



            {{-- LOGO --}}
            <a href="/" class="flex items-center gap-5 font-extrabold text-2xl tracking-tight hover:opacity-90">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        class="h-7 w-7"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <rect x="3" y="2" width="18" height="20" rx="2" />
                        <circle cx="12" cy="13" r="5" />
                        <path d="M9 13c1-1 2-1 3 0s2 1 3 0" />
                    </svg>
                </span>
                FreshWash
            </a>

            {{-- NAV --}}
            <nav class="hidden md:flex items-center gap-12 text-lg font-medium">

                <a
                    href="/#how"
                    class="transition
                        {{ $active === 'how'
                            ? 'text-white'
                            : 'text-white/80 hover:text-white' }}"
                >
                    Kako radi
                </a>

                <a
                    href="{{ route('info.locations.index') }}"
                    class="transition
                        {{ $active === 'locations'
                            ? 'text-white'
                            : 'text-white/80 hover:text-white' }}"
                >
                    Lokacije
                </a>

                <a
                    href="/#pricing"
                    class="transition
                        {{ $active === 'pricing'
                            ? 'text-white'
                            : 'text-white/80 hover:text-white' }}"
                >
                    Cjenik
                </a>

                @auth
                    <a
                        href="/?openApp=1"
                        class="ml-6 inline-flex items-center justify-center
           rounded-xl bg-white text-blue-700
           px-6 py-3 text-base font-semibold
           hover:bg-slate-100 transition"
                    >
                        Otvori aplikaciju
                    </a>
                @else
                    <a href="/login" class="text-white/80 hover:text-white transition">
                        Prijava
                    </a>

                    <a
                        href="/register"
                        class="inline-flex items-center justify-center
                               rounded-2xl bg-white text-blue-700
                               px-6 py-3 font-semibold
                               hover:bg-slate-100 transition"
                    >
                        Registracija
                    </a>
                @endauth
            </nav>
        </div>
    </div>
</header>
