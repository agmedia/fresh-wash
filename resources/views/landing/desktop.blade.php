<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <title>FreshWash – Pametne veš-mašine po gradu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css']) {{-- Tailwind --}}
</head>
<body class="bg-gray-50 text-gray-900">

{{-- HEADER --}}
@include('landing.partials.header', ['active' => 'home'])

{{-- HERO / BANNER --}}
<section class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white">
    <div class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-2 gap-12 items-center">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-6">
                Pametno pranje veša.<br>
                Bez čekanja. Bez kovanica.
            </h1>
            <p class="text-lg opacity-90 mb-8">
                Rezerviraj veš-mašinu u svojoj blizini, plati online i
                otključaj je QR kodom. FreshWash je tvoja praonica u džepu.
            </p>

            <div class="flex gap-4">
                <a href="/register"
                   class="px-6 py-3 rounded-lg bg-white text-blue-600 font-semibold hover:bg-gray-100">
                    Započni odmah
                </a>
                <a href="#how"
                   class="px-6 py-3 rounded-lg border border-white/40 hover:bg-white/10">
                    Kako radi
                </a>
            </div>
        </div>

        <div class="hidden md:block">
            {{-- dummy vizual --}}
            <div class="bg-white/10 rounded-2xl p-8 shadow-2xl">
                <div class="grid grid-cols-2 gap-4">
                    <div class="h-32 rounded-xl bg-white/20"></div>
                    <div class="h-32 rounded-xl bg-white/20"></div>
                    <div class="h-32 rounded-xl bg-white/20"></div>
                    <div class="h-32 rounded-xl bg-white/20"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section id="how" class="py-20">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12">
            Kako FreshWash funkcionira
        </h2>

        <div class="grid md:grid-cols-3 gap-10">
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="text-blue-600 text-2xl font-bold mb-3">1</div>
                <h3 class="font-semibold mb-2">Odaberi lokaciju</h3>
                <p class="text-sm text-gray-600">
                    Pronađi najbližu dostupnu veš-mašinu u svom gradu.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="text-blue-600 text-2xl font-bold mb-3">2</div>
                <h3 class="font-semibold mb-2">Plati online</h3>
                <p class="text-sm text-gray-600">
                    Transparentna cijena s PDV-om. Kartice, online plaćanja.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="text-blue-600 text-2xl font-bold mb-3">3</div>
                <h3 class="font-semibold mb-2">Otključaj QR-om</h3>
                <p class="text-sm text-gray-600">
                    Skeniraj QR kod i započni pranje bez ključeva.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- LOCATIONS (dummy) --}}
<section id="locations" class="bg-gray-100 py-20">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold mb-8">Lokacije po gradu</h2>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h4 class="font-semibold">Zagreb – Centar</h4>
                <p class="text-sm text-gray-600">6 aktivnih mašina</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h4 class="font-semibold">Split – Poljud</h4>
                <p class="text-sm text-gray-600">4 aktivne mašine</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h4 class="font-semibold">Rijeka – Centar</h4>
                <p class="text-sm text-gray-600">3 aktivne mašine</p>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
@include('landing.partials.footer')

</body>
</html>