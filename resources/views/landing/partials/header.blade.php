@php
    $active = $active ?? null; // 'home' | 'locations' | 'pricing' | 'how'
@endphp

<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="text-xl font-extrabold text-blue-600">
            <a href="/" class="hover:opacity-90">FreshWash</a>
        </div>

        <nav class="flex items-center gap-6 text-sm font-medium">
            <a
                    href="/#how"
                    class="{{ $active === 'how' ? 'text-blue-600' : 'hover:text-blue-600' }}"
            >
                Kako radi
            </a>

            <a
                    href="{{ route('info.locations.index') }}"
                    class="{{ $active === 'locations' ? 'text-blue-600' : 'hover:text-blue-600' }}"
            >
                Lokacije
            </a>

            <a
                    href="/#pricing"
                    class="{{ $active === 'pricing' ? 'text-blue-600' : 'hover:text-blue-600' }}"
            >
                Cjenik
            </a>

            @auth
                <a href="/?openApp=1"
                   class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                    Otvori aplikaciju
                </a>
            @else
                <a href="/login" class="hover:text-blue-600">Prijava</a>
                <a href="/register"
                   class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                    Registracija
                </a>
            @endauth
        </nav>
    </div>
</header>