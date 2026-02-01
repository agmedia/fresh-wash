<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <title>FreshWash – Pametna praonica u tvom kvartu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css']) {{-- Tailwind --}}
    <style>
        /* =========================
    STICKY HEADER – SHRINK
    ========================= */

        /* smooth transitions */
        #site-header,
        #site-header * {
            transition: all 0.3s ease;
        }

        /* DEFAULT (top of page) */
        #site-header .header-inner {
            height: 5rem; /* h-20 = 80px */
        }

        /* SCROLLED STATE */
        #site-header.is-scrolled .header-inner {
            height: 3.5rem; /* ~56px */
        }

        /* LOGO TEXT */
        #site-header.is-scrolled a.flex.items-center {
            font-size: 1.125rem; /* slightly smaller than text-xl */
            gap: 0.75rem;
        }

        /* LOGO ICON BOX */
        #site-header.is-scrolled a.flex.items-center span {
            width: 2.25rem;  /* 36px */
            height: 2.25rem; /* 36px */
            border-radius: 0.75rem;
        }

        /* NAV TEXT */
        #site-header.is-scrolled nav {
            font-size: 0.95rem;
        }

        /* PRIMARY CTA (Otvori aplikaciju / Registracija) */
        #site-header.is-scrolled nav a.inline-flex {
            padding-top: 0.4rem;
            padding-bottom: 0.4rem;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }

        /* OPTIONAL: subtle shadow only after scroll */
        #site-header.is-scrolled {
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

@include('landing.partials.header', ['active' => 'home'])

<main>

    {{-- HERO --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-700 via-indigo-700 to-sky-600"></div>
        <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>

        {{-- TOP CONTENT --}}
        <div
            class="relative max-w-7xl mx-auto px-6 pt-24 lg:pt-28 pb-14
               grid lg:grid-cols-12 gap-x-12 gap-y-14 items-start"
        >
            {{-- LEFT --}}
            <div class="lg:col-span-6 text-white">
                <p class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                    Dostupno u više gradova • 0 kovanica • Online plaćanje
                </p>

                <h1 class="mt-6 text-4xl lg:text-6xl font-extrabold leading-tight tracking-tight">
                    Pametno pranje veša,<br class="hidden sm:block">
                    bez čekanja i bez gnjavaže.
                </h1>

                <p class="mt-6 text-lg lg:text-xl text-white/90 max-w-xl">
                    Ubaci odjeću u FreshWash ormarić, prati status u aplikaciji i preuzmi čisto kad ti odgovara.
                </p>

                <div class="mt-10 flex flex-wrap items-center gap-4">
                    <a href="/register"
                       class="px-6 py-3 rounded-xl bg-white text-blue-700 font-semibold hover:bg-slate-100 transition">
                        Započni odmah
                    </a>
                    <a href="#how"
                       class="px-6 py-3 rounded-xl border border-white/30 text-white hover:bg-white/10 transition">
                        Pogledaj kako radi
                    </a>
                    <a href="#locations"
                       class="px-6 py-3 rounded-xl text-white/90 hover:text-white underline underline-offset-4">
                        Provjeri lokacije
                    </a>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="lg:col-span-6">
                <div class="mx-auto max-w-xl">
                    <div class="rounded-3xl bg-white/10 border border-white/20 shadow-2xl overflow-hidden">
                        <div class="p-6 lg:p-8">
                            <div class="flex items-center justify-between">
                                <div class="text-white font-semibold">FreshWash</div>
                                <div class="text-white/70 text-sm">Status narudžbe</div>
                            </div>

                            <div class="mt-6 grid grid-cols-2 gap-4">
                                <div class="rounded-2xl bg-white/10 p-4">
                                    <div class="text-white/70 text-sm">Lokacija</div>
                                    <div class="text-white font-semibold mt-1">Zagreb – Centar</div>
                                </div>

                                <div class="rounded-2xl bg-white/10 p-4">
                                    <div class="text-white/70 text-sm">Ormarić</div>
                                    <div class="text-white font-semibold mt-1">A-12</div>
                                </div>

                                <div class="col-span-2 rounded-2xl bg-white/10 p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="text-white/70 text-sm">Trenutni korak</div>
                                        <span class="text-xs rounded-full bg-emerald-300/20 text-emerald-100 px-3 py-1">
                                        U tijeku
                                    </span>
                                    </div>

                                    <div class="text-white font-semibold mt-2">
                                        Odjeća je zaprimljena i obrađuje se
                                    </div>

                                    <div class="mt-3 h-2 rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-2 w-2/3 bg-white/60 rounded-full"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex gap-3">
                                <button class="w-full rounded-xl bg-white text-blue-700 font-semibold py-3 hover:bg-slate-100 transition">
                                    Otključaj ormarić
                                </button>
                                <button class="w-full rounded-xl border border-white/30 text-white py-3 hover:bg-white/10 transition">
                                    Podrška
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- (toast removed) --}}
                </div>
            </div>
        </div>

        {{-- FULL-WIDTH TRUST STRIP --}}
        <div class="relative border-t border-white/10">
            <div class="absolute inset-0 bg-white/5 backdrop-blur-sm"></div>

            {{-- Icons defined ONCE (3 različite) --}}
            <svg class="sr-only" aria-hidden="true">
                {{-- 1) Clock --}}
                <symbol id="icon-clock" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </symbol>

                {{-- 2) Bolt (za 24/7) --}}
                <symbol id="icon-bolt" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 2L3 14h7l-1 8 12-14h-7l-1-6z" />
                </symbol>

                {{-- 3) Card --}}
                <symbol id="icon-card" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 7h18M3 11h18M7 15h.01M3 5h18a2 2 0 012 2v10a2 2 0 01-2 2H3a2 2 0 01-2-2V7a2 2 0 012-2z" />
                </symbol>
            </svg>

            <div class="relative w-full px-6 py-10">
                <div class="max-w-7xl mx-auto">
                    {{-- KEY: justify-items-center da se ne lijepi lijevo --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-white items-stretch justify-items-center">

                        {{-- ITEM --}}
                        <div class="w-full md:max-w-[360px]">
                            <div class="flex items-center gap-4 h-full">
                                <div class="flex-shrink-0 flex h-12 w-12 items-center justify-center rounded-xl bg-white/15">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" aria-hidden="true">
                                        <use href="#icon-clock"></use>
                                    </svg>
                                </div>
                                <div class="flex flex-col justify-center min-h-[48px]">
                                    <div class="text-2xl font-bold leading-none">2–3h</div>
                                    <div class="mt-1 text-sm text-white/80 leading-tight">
                                        prosječno vrijeme usluge
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ITEM --}}
                        <div class="w-full md:max-w-[360px]">
                            <div class="flex items-center gap-4 h-full">
                                <div class="flex-shrink-0 flex h-12 w-12 items-center justify-center rounded-xl bg-white/15">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" aria-hidden="true">
                                        <use href="#icon-bolt"></use>
                                    </svg>
                                </div>
                                <div class="flex flex-col justify-center min-h-[48px]">
                                    <div class="text-2xl font-bold leading-none">24/7</div>
                                    <div class="mt-1 text-sm text-white/80 leading-tight">
                                        preuzimanje kada želiš
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ITEM --}}
                        <div class="w-full md:max-w-[360px]">
                            <div class="flex items-center gap-4 h-full">
                                <div class="flex-shrink-0 flex h-12 w-12 items-center justify-center rounded-xl bg-white/15">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" aria-hidden="true">
                                        <use href="#icon-card"></use>
                                    </svg>
                                </div>
                                <div class="flex flex-col justify-center min-h-[48px]">
                                    <div class="text-2xl font-bold leading-none">100%</div>
                                    <div class="mt-1 text-sm text-white/80 leading-tight">
                                        online i bez gotovine
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </section>


    {{-- WHY / BENEFITS --}}
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-12 gap-14 items-start">

                {{-- LEFT / INTRO --}}
                <div class="lg:col-span-4">
                    <h2 class="text-4xl font-extrabold tracking-tight text-slate-900">
                        Zašto FreshWash?
                    </h2>

                    <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                        Dizajnirano za brz, moderan život.
                        Bez čekanja u praonici, bez gotovine i bez
                        <span class="font-medium text-slate-800">“a jel slobodno?”</span> poziva.
                    </p>

                    <div class="mt-8">
                        <a href="/?openApp=1"
                           class="inline-flex items-center gap-3
              rounded-2xl bg-blue-600 text-white
              px-7 py-3.5 text-base font-semibold
              hover:bg-blue-700 transition shadow-sm">
                            {{-- Icon: mobile / app --}}
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <rect x="5" y="2" width="14" height="20" rx="2" />
                                <path d="M12 18h.01" />
                            </svg>

                            Otvori aplikaciju
                        </a>
                    </div>

                </div>

                {{-- RIGHT / CARDS --}}
                <div class="lg:col-span-8 grid sm:grid-cols-2 gap-8">

                    {{-- CARD --}}
                    <div class="group rounded-3xl bg-white p-7 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600/10 text-blue-700">
                                {{-- Clock --}}
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900">
                                Bez čekanja
                            </h3>
                        </div>

                        <p class="mt-4 text-slate-600 leading-relaxed">
                            Ubaci rublje u ormarić kad ti odgovara i
                            preuzmi čisto bez gubljenja vremena.
                        </p>
                    </div>

                    {{-- CARD --}}
                    <div class="group rounded-3xl bg-white p-7 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600/10 text-indigo-700">
                                {{-- Credit card --}}
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 7h18M3 11h18M7 15h.01M3 5h18a2 2 0 012 2v10a2 2 0 01-2 2H3a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900">
                                Online plaćanje
                            </h3>
                        </div>

                        <p class="mt-4 text-slate-600 leading-relaxed">
                            Kartice i digitalna plaćanja, jasna cijena
                            unaprijed – bez kovanica i skrivenih troškova.
                        </p>
                    </div>

                    {{-- CARD --}}
                    <div class="group rounded-3xl bg-white p-7 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600/10 text-emerald-700">
                                {{-- Bell / status --}}
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 17h5l-1.4-1.4A2 2 0 0118 14V11a6 6 0 10-12 0v3a2 2 0 01-.6 1.6L4 17h5"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 17a3 3 0 006 0"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900">
                                Praćenje statusa
                            </h3>
                        </div>

                        <p class="mt-4 text-slate-600 leading-relaxed">
                            Aplikacija te obavještava kad je rublje zaprimljeno
                            i kad je spremno za preuzimanje.
                        </p>
                    </div>

                    {{-- CARD --}}
                    <div class="group rounded-3xl bg-white p-7 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-600/10 text-sky-700">
                                {{-- Lock --}}
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M7 11V7a5 5 0 0110 0v4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900">
                                Sigurno preuzimanje
                            </h3>
                        </div>

                        <p class="mt-4 text-slate-600 leading-relaxed">
                            Ormarić se otključava samo putem aplikacije
                            (QR kod ili gumb).
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>


    {{-- HOW IT WORKS --}}
    <section id="how" class="py-24 bg-slate-100/70 border-y border-slate-200/60">
        <div class="max-w-7xl mx-auto px-6">

            {{-- HEADER --}}
            <div class="flex items-end justify-between gap-10">
                <div>
                    <h2 class="text-4xl font-extrabold tracking-tight text-slate-900">
                        Kako radi
                    </h2>

                    <p class="mt-5 text-lg text-slate-600 max-w-2xl leading-relaxed">
                        Jednostavan “drop-off / pick-up” proces.
                        Otvori aplikaciju, ubaci odjeću u ormarić i preuzmi čisto kad dobiješ obavijest.
                    </p>
                </div>

                <a href="/?openApp=1"
                   class="inline-flex items-center gap-3
          rounded-2xl bg-blue-600 text-white
          px-7 py-3.5 text-base font-semibold
          hover:bg-blue-700 transition">
                    Otvori aplikaciju
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <rect x="5" y="2" width="14" height="20" rx="2" />
                        <path d="M12 18h.01" />
                    </svg>
                </a>

            </div>

            {{-- STEPS --}}
            <div class="mt-16 grid lg:grid-cols-4 gap-8">

                {{-- STEP 1 --}}
                <div class="group rounded-3xl bg-white p-7 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600/10 text-blue-700">
                            {{-- App icon --}}
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <rect x="5" y="2" width="14" height="20" rx="2" />
                                <path d="M12 18h.01" />
                            </svg>
                        </div>
                        <span class="text-slate-300 font-bold text-xl">01</span>
                    </div>

                    <h3 class="mt-6 text-xl font-semibold text-slate-900">
                        Otvori aplikaciju
                    </h3>

                    <p class="mt-3 text-slate-600 leading-relaxed">
                        Odaberi lokaciju i započni narudžbu u par klikova.
                    </p>
                </div>

                {{-- STEP 2 --}}
                <div class="group rounded-3xl bg-white p-7 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600/10 text-indigo-700">
                            {{-- Locker --}}
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="2" width="18" height="20" rx="2"/>
                                <circle cx="12" cy="13" r="3"/>
                            </svg>
                        </div>
                        <span class="text-slate-300 font-bold text-xl">02</span>
                    </div>

                    <h3 class="mt-6 text-xl font-semibold text-slate-900">
                        Ubaci odjeću
                    </h3>

                    <p class="mt-3 text-slate-600 leading-relaxed">
                        Ormarić se automatski otključa – ubaci rublje i zatvori vrata.
                    </p>
                </div>

                {{-- STEP 3 --}}
                <div class="group rounded-3xl bg-white p-7 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600/10 text-emerald-700">
                            {{-- Notifications --}}
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 17h5l-1.4-1.4A2 2 0 0118 14V11a6 6 0 10-12 0v3a2 2 0 01-.6 1.6L4 17h5"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 17a3 3 0 006 0"/>
                            </svg>
                        </div>
                        <span class="text-slate-300 font-bold text-xl">03</span>
                    </div>

                    <h3 class="mt-6 text-xl font-semibold text-slate-900">
                        Praćenje & obavijesti
                    </h3>

                    <p class="mt-3 text-slate-600 leading-relaxed">
                        Prati status i dobiješ obavijest kad je rublje spremno.
                    </p>
                </div>

                {{-- STEP 4 --}}
                <div class="group rounded-3xl bg-white p-7 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-600/10 text-sky-700">
                            {{-- Unlock --}}
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0110 0"/>
                            </svg>
                        </div>
                        <span class="text-slate-300 font-bold text-xl">04</span>
                    </div>

                    <h3 class="mt-6 text-xl font-semibold text-slate-900">
                        Preuzmi kad želiš
                    </h3>

                    <p class="mt-3 text-slate-600 leading-relaxed">
                        Dođeš do ormarića i jednim klikom u aplikaciji ga otključaš.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- LOCATIONS --}}

    <section id="locations" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Header --}}
            <div class="flex items-end justify-between gap-10">
                <div>
                    <h2 class="text-4xl font-extrabold tracking-tight text-slate-900">
                        Lokacije
                    </h2>
                    <p class="mt-5 text-lg text-slate-600 max-w-2xl leading-relaxed">
                        Odaberi najbliži FreshWash ormarić i provjeri dostupnost u realnom vremenu.
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <a href="#pricing"
                       class="inline-flex items-center gap-3 rounded-2xl
                          bg-white border border-slate-200
                          px-7 py-3.5 text-base font-semibold text-slate-900
                          hover:bg-slate-50 transition">
                        {{-- icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 1v22"/>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                        Cijene
                    </a>

                    <a href="/?openApp=1"
                       class="inline-flex items-center gap-3 rounded-2xl
                          bg-blue-600 text-white
                          px-7 py-3.5 text-base font-semibold
                          hover:bg-blue-700 transition">
                        {{-- icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <rect x="5" y="2" width="14" height="20" rx="2" />
                            <path d="M12 18h.01" />
                        </svg>
                        Provjeri dostupnost
                    </a>
                </div>
            </div>

            {{-- Location cards --}}
            <div class="mt-16 grid lg:grid-cols-3 gap-8">

                {{-- Card --}}
                <div class="group rounded-3xl bg-white p-7 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-600/10 text-blue-700">
                                    {{-- map pin --}}
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11z"/>
                                        <circle cx="12" cy="10" r="2.5"/>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-semibold text-slate-900">Zagreb – Centar</h4>
                            </div>

                            <div class="mt-4 flex items-center gap-2 text-slate-600">
                                {{-- locker icon --}}
                                <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="2" width="18" height="20" rx="2"/>
                                    <path d="M12 7h.01M12 13h.01M12 19h.01"/>
                                </svg>
                                <span class="text-base">6 aktivnih ormarića</span>
                            </div>
                        </div>

                        <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-4 py-1.5 border border-emerald-100 text-sm font-semibold">
                        Dostupno
                    </span>
                    </div>

                    <div class="mt-6 text-base text-slate-600">
                        Radno: 0–24 <span class="text-slate-300 px-2">•</span> Preuzimanje 24/7
                    </div>

                    <a href="/?openApp=1"
                       class="mt-7 inline-flex items-center gap-2 text-blue-700 font-semibold hover:text-blue-800 transition">
                        Otvori u aplikaciji
                        <span class="opacity-60">→</span>
                    </a>
                </div>

                {{-- Card --}}
                <div class="group rounded-3xl bg-white p-7 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600/10 text-indigo-700">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11z"/>
                                        <circle cx="12" cy="10" r="2.5"/>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-semibold text-slate-900">Split – Poljud</h4>
                            </div>

                            <div class="mt-4 flex items-center gap-2 text-slate-600">
                                <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="2" width="18" height="20" rx="2"/>
                                    <path d="M12 7h.01M12 13h.01M12 19h.01"/>
                                </svg>
                                <span class="text-base">4 aktivna ormarića</span>
                            </div>
                        </div>

                        <span class="inline-flex items-center rounded-full bg-amber-50 text-amber-800 px-4 py-1.5 border border-amber-100 text-sm font-semibold">
                        Ograničeno
                    </span>
                    </div>

                    <div class="mt-6 text-base text-slate-600">
                        Radno: 7–23 <span class="text-slate-300 px-2">•</span> Preuzimanje 24/7
                    </div>

                    <a href="/?openApp=1"
                       class="mt-7 inline-flex items-center gap-2 text-blue-700 font-semibold hover:text-blue-800 transition">
                        Otvori u aplikaciji
                        <span class="opacity-60">→</span>
                    </a>
                </div>

                {{-- Card --}}
                <div class="group rounded-3xl bg-white p-7 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-600/10 text-sky-700">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11z"/>
                                        <circle cx="12" cy="10" r="2.5"/>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-semibold text-slate-900">Rijeka – Centar</h4>
                            </div>

                            <div class="mt-4 flex items-center gap-2 text-slate-600">
                                <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="2" width="18" height="20" rx="2"/>
                                    <path d="M12 7h.01M12 13h.01M12 19h.01"/>
                                </svg>
                                <span class="text-base">3 aktivna ormarića</span>
                            </div>
                        </div>

                        <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-4 py-1.5 border border-emerald-100 text-sm font-semibold">
                        Dostupno
                    </span>
                    </div>

                    <div class="mt-6 text-base text-slate-600">
                        Radno: 0–24 <span class="text-slate-300 px-2">•</span> Preuzimanje 24/7
                    </div>

                    <a href="/?openApp=1"
                       class="mt-7 inline-flex items-center gap-2 text-blue-700 font-semibold hover:text-blue-800 transition">
                        Otvori u aplikaciji
                        <span class="opacity-60">→</span>
                    </a>
                </div>

            </div>

            {{-- MAP (Zagreb example, black/white) --}}
            <div class="mt-12 rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-white">
                <div class="px-7 py-6 border-b border-slate-200/60 flex items-center justify-between">
                    <div>
                        <div class="text-xl font-semibold text-slate-900">Karta – Zagreb</div>
                        <div class="mt-1 text-base text-slate-600">Primjer prikaza (crno-bijelo)</div>
                    </div>

                    <a href="https://www.google.com/maps?q=Zagreb&z=12"
                       target="_blank"
                       class="inline-flex items-center gap-2 text-blue-700 font-semibold hover:text-blue-800 transition">
                        Otvori u Google Maps
                        <span class="opacity-60">↗</span>
                    </a>
                </div>

                <div class="relative h-[420px]">
                    {{-- Tailwind filter utilities: grayscale + contrast --}}
                    <iframe
                        title="Karta Zagreba"
                        class="absolute inset-0 w-full h-full filter grayscale contrast-125 saturate-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps?q=Zagreb&z=12&output=embed">
                    </iframe>

                    {{-- subtle overlay to match style --}}
                    <div class="pointer-events-none absolute inset-0 bg-slate-900/5"></div>
                </div>
            </div>

        </div>
    </section>


    {{-- PRICING --}}
    <section id="pricing" class="py-24 bg-white border-y border-slate-200/60">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Header --}}
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-900">
                    Jednostavne cijene
                </h2>
                <p class="mt-5 text-lg text-slate-600 leading-relaxed">
                    Bez skrivenih troškova. Sve je jasno prije potvrde narudžbe.
                </p>
            </div>

            {{-- Cards --}}
            <div class="mt-16 grid lg:grid-cols-3 gap-8 max-w-6xl mx-auto">

                {{-- BASIC --}}
                <div class="group rounded-3xl border border-slate-200/70 bg-white p-8 hover:shadow-md transition">
                    <h3 class="text-xl font-semibold text-slate-900">
                        Basic
                    </h3>

                    <div class="mt-5 flex items-end gap-2">
                        <div class="text-5xl font-extrabold text-slate-900">€X</div>
                        <span class="text-slate-500 mb-1">/ ciklus</span>
                    </div>

                    <p class="mt-3 text-base text-slate-600">
                        Za svakodnevno, brzo pranje.
                    </p>

                    <ul class="mt-8 space-y-4 text-base text-slate-700">
                        <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                            ✓
                        </span>
                            Standardni ciklus pranja
                        </li>
                        <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                            ✓
                        </span>
                            Obavijesti u aplikaciji
                        </li>
                        <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                            ✓
                        </span>
                            Preuzimanje 24/7
                        </li>
                    </ul>

                    <a href="/?openApp=1"
                       class="mt-10 inline-flex w-full items-center justify-center gap-3
                          rounded-2xl bg-slate-900 text-white
                          px-6 py-3.5 text-base font-semibold
                          hover:bg-slate-800 transition">
                        Otvori aplikaciju
                    </a>
                </div>

                {{-- PLUS (HIGHLIGHT) --}}
                <div class="relative group rounded-3xl border border-blue-300 bg-blue-50/60 p-8 shadow-sm hover:shadow-md transition">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <span class="inline-flex items-center rounded-full bg-blue-700 text-white px-4 py-1.5 text-sm font-semibold">
                        Najpopularnije
                    </span>
                    </div>

                    <h3 class="mt-4 text-xl font-semibold text-slate-900">
                        Plus
                    </h3>

                    <div class="mt-5 flex items-end gap-2">
                        <div class="text-5xl font-extrabold text-slate-900">€Y</div>
                        <span class="text-slate-500 mb-1">/ ciklus</span>
                    </div>

                    <p class="mt-3 text-base text-slate-600">
                        Više brzine i prioritet.
                    </p>

                    <ul class="mt-8 space-y-4 text-base text-slate-700">
                        <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600/10 text-blue-700">
                            ⚡
                        </span>
                            Brži turnaround
                        </li>
                        <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600/10 text-blue-700">
                            ⭐
                        </span>
                            Prioritetna obrada
                        </li>
                        <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600/10 text-blue-700">
                            💬
                        </span>
                            Podrška u aplikaciji
                        </li>
                    </ul>

                    <a href="/?openApp=1"
                       class="mt-10 inline-flex w-full items-center justify-center gap-3
                          rounded-2xl bg-blue-600 text-white
                          px-6 py-3.5 text-base font-semibold
                          hover:bg-blue-700 transition">
                        Otvori aplikaciju
                    </a>
                </div>

                {{-- BUSINESS --}}
                <div class="group rounded-3xl border border-slate-200/70 bg-white p-8 hover:shadow-md transition">
                    <h3 class="text-xl font-semibold text-slate-900">
                        Business
                    </h3>

                    <div class="mt-5 flex items-end gap-2">
                        <div class="text-5xl font-extrabold text-slate-900">€Z</div>
                        <span class="text-slate-500 mb-1">/ dogovor</span>
                    </div>

                    <p class="mt-3 text-base text-slate-600">
                        Za apartmane, hotele i timove.
                    </p>

                    <ul class="mt-8 space-y-4 text-base text-slate-700">
                        <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                            🧾
                        </span>
                            Fakturiranje
                        </li>
                        <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                            👥
                        </span>
                            Više korisnika
                        </li>
                        <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                            📦
                        </span>
                            Dogovorene količine
                        </li>
                    </ul>

                    <a href="/info/kontakt"
                       class="mt-10 inline-flex w-full items-center justify-center gap-3
                          rounded-2xl bg-slate-900 text-white
                          px-6 py-3.5 text-base font-semibold
                          hover:bg-slate-800 transition">
                        Kontaktiraj nas
                    </a>
                </div>

            </div>
        </div>
    </section>


    {{-- TESTIMONIALS --}}
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-end justify-between gap-10">
                <div>
                    <h2 class="text-4xl font-extrabold tracking-tight text-slate-900">
                        Što kažu korisnici
                    </h2>
                    <p class="mt-5 text-lg text-slate-600 leading-relaxed">
                        Kratko, konkretno i bez marketinga.
                    </p>
                </div>
            </div>

            <div class="mt-16 grid lg:grid-cols-3 gap-8">
                {{-- Card --}}
                <figure class="rounded-3xl bg-white p-8 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        {{-- stars --}}
                        <div class="flex items-center gap-1 text-amber-500">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                        </div>
                        {{-- quote icon --}}
                        <svg class="h-6 w-6 text-slate-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M7.5 10.5H5A3 3 0 0 0 2 13.5V19h6v-5.5a3 3 0 0 0-3-3zM19 10.5h-2.5a3 3 0 0 0-3 3V19h6v-5.5a3 3 0 0 0-3-3z"/>
                        </svg>
                    </div>

                    <blockquote class="mt-6 text-lg text-slate-700 leading-relaxed">
                        “Ubacio veš usput, pokupio navečer. Najbolje mi je što nema čekanja.”
                    </blockquote>

                    <figcaption class="mt-7 flex items-center justify-between">
                        <div>
                            <div class="font-semibold text-slate-900">Marko</div>
                            <div class="text-sm text-slate-500">Zagreb</div>
                        </div>
                        <span class="text-sm text-slate-500">Basic</span>
                    </figcaption>
                </figure>

                <figure class="rounded-3xl bg-white p-8 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1 text-amber-500">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                        </div>
                        <svg class="h-6 w-6 text-slate-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M7.5 10.5H5A3 3 0 0 0 2 13.5V19h6v-5.5a3 3 0 0 0-3-3zM19 10.5h-2.5a3 3 0 0 0-3 3V19h6v-5.5a3 3 0 0 0-3-3z"/>
                        </svg>
                    </div>

                    <blockquote class="mt-6 text-lg text-slate-700 leading-relaxed">
                        “Plaćanje karticom i gotova stvar. App me odmah obavijestila kad je spremno.”
                    </blockquote>

                    <figcaption class="mt-7 flex items-center justify-between">
                        <div>
                            <div class="font-semibold text-slate-900">Ivana</div>
                            <div class="text-sm text-slate-500">Split</div>
                        </div>
                        <span class="text-sm text-slate-500">Plus</span>
                    </figcaption>
                </figure>

                <figure class="rounded-3xl bg-white p-8 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1 text-amber-500">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                        </div>
                        <svg class="h-6 w-6 text-slate-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M7.5 10.5H5A3 3 0 0 0 2 13.5V19h6v-5.5a3 3 0 0 0-3-3zM19 10.5h-2.5a3 3 0 0 0-3 3V19h6v-5.5a3 3 0 0 0-3-3z"/>
                        </svg>
                    </div>

                    <blockquote class="mt-6 text-lg text-slate-700 leading-relaxed">
                        “Lokacija blizu posla. Preuzimanje 0–24 mi je spas.”
                    </blockquote>

                    <figcaption class="mt-7 flex items-center justify-between">
                        <div>
                            <div class="font-semibold text-slate-900">Ana</div>
                            <div class="text-sm text-slate-500">Rijeka</div>
                        </div>
                        <span class="text-sm text-slate-500">Basic</span>
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>
    {{-- FAQ --}}
    <section class="py-24 bg-slate-100/70 border-y border-slate-200/60">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-3xl">
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-900">
                    FAQ
                </h2>
                <p class="mt-5 text-lg text-slate-600 leading-relaxed">
                    Najčešća pitanja prije prve narudžbe.
                </p>
            </div>

            <div class="mt-16 grid lg:grid-cols-2 gap-8">
                {{-- Item --}}
                <div class="rounded-3xl bg-white p-8 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600/10 text-blue-700">
                            {{-- unlock --}}
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0110 0"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-slate-900">Kako otključavam ormarić?</h3>
                            <p class="mt-3 text-base text-slate-600 leading-relaxed">
                                U aplikaciji dobiješ gumb ili QR kod za otključavanje na lokaciji.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-8 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600/10 text-emerald-700">
                            {{-- bell --}}
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 17h5l-1.4-1.4A2 2 0 0118 14V11a6 6 0 10-12 0v3a2 2 0 01-.6 1.6L4 17h5"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 17a3 3 0 006 0"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-slate-900">Kad dobijem obavijest?</h3>
                            <p class="mt-3 text-base text-slate-600 leading-relaxed">
                                Kad je odjeća zaprimljena i kad je spremna za preuzimanje.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-8 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-600/10 text-sky-700">
                            {{-- clock --}}
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-slate-900">Mogu li preuzeti u bilo koje vrijeme?</h3>
                            <p class="mt-3 text-base text-slate-600 leading-relaxed">
                                Da, preuzimanje može biti 24/7 (ovisno o lokaciji).
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-8 border border-slate-200/60 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600/10 text-indigo-700">
                            {{-- support --}}
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M18 10a6 6 0 10-12 0v5a2 2 0 002 2h1v-7H8a2 2 0 00-2 2v2"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16 17h1a2 2 0 002-2v-2a2 2 0 00-2-2h-1"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-slate-900">Što ako zapne aplikacija?</h3>
                            <p class="mt-3 text-base text-slate-600 leading-relaxed">
                                U “Podrška” sekciji može biti chat/poziv (ovisno o vašoj implementaciji).
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- FINAL CTA --}}
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="relative overflow-hidden rounded-3xl border border-slate-200/60">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-700 via-indigo-700 to-sky-600"></div>
                <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>

                <div class="relative p-12 lg:p-16 grid lg:grid-cols-12 gap-10 items-center text-white">
                    <div class="lg:col-span-8">
                        <h2 class="text-4xl font-extrabold tracking-tight">
                            Spreman za prvi FreshWash?
                        </h2>
                        <p class="mt-5 text-lg text-white/90 max-w-2xl leading-relaxed">
                            Odaberi lokaciju, ubaci odjeću u ormarić i prati status u aplikaciji.
                            Preuzmi čisto kad ti odgovara.
                        </p>
                    </div>

                    <div class="lg:col-span-4 flex flex-col gap-4">
                        <a href="/?openApp=1"
                           class="inline-flex items-center justify-center gap-3
                              rounded-2xl bg-white text-blue-700
                              px-7 py-3.5 text-base font-semibold
                              hover:bg-slate-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="5" y="2" width="14" height="20" rx="2" />
                                <path d="M12 18h.01" />
                            </svg>
                            Otvori aplikaciju
                        </a>

                        <a href="#locations"
                           class="inline-flex items-center justify-center gap-3
                              rounded-2xl border border-white/25
                              px-7 py-3.5 text-base font-semibold
                              hover:bg-white/10 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11z"/>
                                <circle cx="12" cy="10" r="2.5"/>
                            </svg>
                            Pronađi lokaciju
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


</main>

@include('landing.partials.footer')

<script>
    const header = document.getElementById('site-header');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 40) {
            header.classList.add('is-scrolled');
        } else {
            header.classList.remove('is-scrolled');
        }
    });
</script>


</body>
</html>
