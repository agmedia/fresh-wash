<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <title>FreshWash – Lokacije</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css']) {{-- Tailwind --}}
</head>
<body class="bg-gray-50 text-gray-900">

{{-- HEADER --}}
@include('landing.partials.header', ['active' => 'locations'])

@php
    $first = $locations->first();

    $firstAddress = $first
        ? trim(implode(', ', array_filter([
            $first->address_line1 ?? null,
            $first->address_line2 ?? null,
            trim(implode(' ', array_filter([$first->postal_code ?? null, $first->city ?? null]))),
            $first->country_code ?? null,
        ])))
        : null;

    $firstLat = $first->lat ?? null;
    $firstLng = $first->lng ?? null;

    $firstMapEmbed = null;
    if ($first) {
        if ($firstLat && $firstLng) {
            $firstMapEmbed = 'https://www.google.com/maps?q=' . urlencode($firstLat . ',' . $firstLng) . '&z=15&output=embed';
        } elseif ($firstAddress) {
            $firstMapEmbed = 'https://www.google.com/maps?q=' . urlencode($firstAddress) . '&z=15&output=embed';
        }
    }
@endphp

{{-- HERO / BANNER --}}
<section class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white">
    <div class="max-w-7xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-center">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-6">
                Lokacije FreshWash<br>
                u tvom gradu
            </h1>
            <p class="text-lg opacity-90 mb-8">
                Pronađi najbližu lokaciju, provjeri adresu i kreni u rezervaciju preko aplikacije.
            </p>

            <div class="flex flex-wrap gap-4">
                <a href="/?openApp=1"
                   class="px-6 py-3 rounded-lg bg-white text-blue-600 font-semibold hover:bg-gray-100">
                    Rezerviraj (PWA)
                </a>
                <a href="/#how"
                   class="px-6 py-3 rounded-lg border border-white/40 hover:bg-white/10">
                    Kako radi
                </a>
            </div>

            <div class="mt-8 flex items-center gap-3 text-white/90 text-sm">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/15 font-bold">
                    {{ $locations->count() }}
                </span>
                <span>
                    Aktivnih lokacija u sustavu
                </span>
            </div>
        </div>

        <div class="hidden md:block">
            <div class="bg-white/10 rounded-2xl p-6 shadow-2xl">
                <div class="text-sm font-semibold mb-3">
                    {{ $first?->name ?? 'Nema lokacija' }}
                </div>
                <div class="text-xs opacity-90 mb-4">
                    {{ $firstAddress ?? '—' }}
                </div>

                <div class="rounded-xl overflow-hidden bg-white/10">
                    @if($firstMapEmbed)
                        <iframe
                                src="{{ $firstMapEmbed }}"
                                width="100%"
                                height="280"
                                style="border:0"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @else
                        <div class="h-[280px] flex items-center justify-center text-white/80 text-sm">
                            Nema podataka za mapu.
                        </div>
                    @endif
                </div>

                @if($first)
                    @php
                        $firstMapsLink = ($firstLat && $firstLng)
                            ? 'https://www.google.com/maps/search/?api=1&query=' . $firstLat . ',' . $firstLng
                            : ($firstAddress ? 'https://www.google.com/maps/search/?api=1&query=' . urlencode($firstAddress) : '#');
                    @endphp
                    <div class="mt-4">
                        <a href="{{ $firstMapsLink }}" target="_blank" rel="noopener"
                           class="inline-flex px-4 py-2 rounded-md bg-white text-blue-600 font-semibold hover:bg-gray-100">
                            Otvori u Google Maps
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- LOCATIONS LIST --}}
<section id="locations" class="bg-gray-100 py-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between gap-6 mb-8">
            <div>
                <h2 class="text-3xl font-bold">Lokacije</h2>
                <p class="text-sm text-gray-600 mt-2">
                    Popis lokacija i adresa. Klik “Maps” otvara navigaciju.
                </p>
            </div>

            <div class="text-sm text-gray-600">
                Ukupno: <span class="font-semibold text-gray-900">{{ $locations->count() }}</span>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($locations as $l)
                @php
                    $addr = trim(implode(', ', array_filter([
                        $l->address_line1 ?? null,
                        $l->address_line2 ?? null,
                        trim(implode(' ', array_filter([$l->postal_code ?? null, $l->city ?? null]))),
                        $l->country_code ?? null,
                    ])));

                    $lat = $l->lat ?? null;
                    $lng = $l->lng ?? null;

                    $maps = ($lat && $lng)
                        ? 'https://www.google.com/maps/search/?api=1&query=' . $lat . ',' . $lng
                        : ($addr ? 'https://www.google.com/maps/search/?api=1&query=' . urlencode($addr) : '#');
                @endphp

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h4 class="font-semibold text-lg">{{ $l->name }}</h4>
                    <p class="text-sm text-gray-600 mt-2">
                        {{ $addr ?: 'Adresa nije unesena.' }}
                    </p>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ $maps }}" target="_blank" rel="noopener"
                           class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 text-sm font-semibold">
                            Maps
                        </a>

                        <a href="/?openApp=1"
                           class="px-4 py-2 rounded-md bg-gray-900 text-white hover:bg-gray-800 text-sm font-semibold">
                            Rezerviraj
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl p-6 shadow-sm col-span-full">
                    Trenutno nema aktivnih lokacija.
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="bg-white rounded-2xl p-10 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
            <div>
                <h3 class="text-2xl font-bold">Spreman za pranje bez čekanja?</h3>
                <p class="text-sm text-gray-600 mt-2">
                    U aplikaciji odabereš lokaciju, mašinu i servis, platiš i otključaš QR kodom.
                </p>
            </div>

            <div class="flex gap-4">
                <a href="/register"
                   class="px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    Započni odmah
                </a>
                <a href="/?openApp=1"
                   class="px-6 py-3 rounded-lg bg-gray-900 text-white font-semibold hover:bg-gray-800">
                    Otvori aplikaciju
                </a>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
@include('landing.partials.footer')

</body>
</html>