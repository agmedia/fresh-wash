<footer class="relative bg-slate-950 text-slate-300">
    {{-- subtle top border / glow --}}
    <div class="absolute inset-x-0 top-0 h-px bg-white/10"></div>
    <div class="absolute -top-24 left-1/2 -translate-x-1/2 h-48 w-[42rem] rounded-full bg-blue-500/10 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-16">
        <div class="grid gap-14 lg:grid-cols-12">

            {{-- BRAND --}}
            <div class="lg:col-span-4">
                <a href="/" class="inline-flex items-center gap-4 text-white hover:opacity-90">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 border border-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.6"
                             stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="2" width="18" height="20" rx="2" />
                            <circle cx="12" cy="13" r="5" />
                            <path d="M9 13c1-1 2-1 3 0s2 1 3 0" />
                        </svg>
                    </span>
                    <span class="text-2xl font-extrabold tracking-tight">FreshWash</span>
                </a>

                <p class="mt-5 text-base text-slate-400 leading-normal max-w-sm">
                    Pametna praonica u tvom kvartu. Ubaci odjeću u ormarić, plati online i preuzmi kad ti odgovara.
                </p>

                {{-- Primary actions --}}
                <div class="mt-7 flex flex-wrap gap-4">
                    <a href="/?openApp=1"
                       class="inline-flex items-center justify-center rounded-xl bg-white text-blue-700 px-6 py-3 text-base font-semibold hover:bg-slate-100 transition">
                        Otvori aplikaciju
                    </a>
                    <a href="/info/kontakt"
                       class="inline-flex items-center justify-center rounded-xl bg-white/10 border border-white/10 px-6 py-3 text-base font-semibold text-white hover:bg-white/15 transition">
                        Kontakt
                    </a>
                </div>


                {{-- Social --}}
                <div class="mt-10">
                    <div class="text-sm font-semibold text-slate-400 tracking-wider uppercase">
                        Prati nas
                    </div>

                    <div class="mt-4 flex items-center gap-4">
                        {{-- Instagram --}}
                        <a href="https://instagram.com"
                           target="_blank"
                           aria-label="Instagram"
                           class="h-11 w-11 inline-flex items-center justify-center rounded-xl
                  bg-white/5 border border-white/10
                  hover:bg-white/10 hover:text-white transition">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <rect x="2" y="2" width="20" height="20" rx="5"/>
                                <circle cx="12" cy="12" r="4"/>
                                <circle cx="17" cy="7" r="0.8" fill="currentColor"/>
                            </svg>
                        </a>

                        {{-- Facebook --}}
                        <a href="https://facebook.com"
                           target="_blank"
                           aria-label="Facebook"
                           class="h-11 w-11 inline-flex items-center justify-center rounded-xl
                  bg-white/5 border border-white/10
                  hover:bg-white/10 hover:text-white transition">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15 3h-3a5 5 0 0 0-5 5v3H4v4h3v6h4v-6h3.1l.9-4H11V8a1 1 0 0 1 1-1h3V3z"/>
                            </svg>
                        </a>

                        {{-- YouTube --}}
                        <a href="https://youtube.com"
                           target="_blank"
                           aria-label="YouTube"
                           class="h-11 w-11 inline-flex items-center justify-center rounded-xl
                  bg-white/5 border border-white/10
                  hover:bg-white/10 hover:text-white transition">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23 7s-0.2-1.6-0.8-2.3c-0.8-0.9-1.7-0.9-2.1-1C17.1 3.4 12 3.4 12 3.4h0s-5.1 0-8.1 0.3c-0.4 0.1-1.3 0.1-2.1 1C1.2 5.4 1 7 1 7S0.8 8.8 0.8 10.6v1.7c0 1.8 0.2 3.6 0.2 3.6s0.2 1.6 0.8 2.3c0.8 0.9 1.9 0.9 2.4 1C6 19.6 12 19.6 12 19.6s5.1 0 8.1-0.3c0.4-0.1 1.3-0.1 2.1-1 0.6-0.7 0.8-2.3 0.8-2.3s0.2-1.8 0.2-3.6v-1.7C23.2 8.8 23 7 23 7zM9.75 14.6V8.9l5.3 2.85-5.3 2.85z"/>
                            </svg>
                        </a>

                        {{-- LinkedIn --}}
                        <a href="https://linkedin.com"
                           target="_blank"
                           aria-label="LinkedIn"
                           class="h-11 w-11 inline-flex items-center justify-center rounded-xl
                  bg-white/5 border border-white/10
                  hover:bg-white/10 hover:text-white transition">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4.98 3.5C4.98 4.88 3.88 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM0.5 23.5h4V7.5h-4v16zM8.5 7.5h3.8v2.2h.1c.5-1 1.8-2.2 3.7-2.2 4 0 4.7 2.6 4.7 6v9.5h-4v-8.4c0-2 0-4.6-2.8-4.6s-3.2 2.2-3.2 4.4v8.6h-4v-16z"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>

            {{-- LINKS --}}
            <div class="lg:col-span-5 grid gap-12 sm:grid-cols-2">
                {{-- Info --}}
                <div>
                    <div class="text-white font-semibold mb-5 text-lg">Info</div>
                    <ul class="space-y-4 text-base text-slate-400">
                        <li><a href="/#how" class="hover:text-white transition">Kako radi</a></li>
                        <li><a href="{{ route('info.locations.index') }}" class="hover:text-white transition">Lokacije</a></li>
                        <li><a href="/#pricing" class="hover:text-white transition">Cjenik</a></li>
                        <li><a href="/info/faq" class="hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>

                {{-- Pravno --}}
                <div>
                    <div class="text-white font-semibold mb-5 text-lg">Pravno</div>
                    <ul class="space-y-4 text-base text-slate-400">
                        <li><a href="/info/uvjeti" class="hover:text-white transition">Uvjeti korištenja</a></li>
                        <li><a href="/info/privatnost" class="hover:text-white transition">Pravila privatnosti</a></li>
                        <li><a href="/info/cookies" class="hover:text-white transition">Kolačići</a></li>
                        <li><a href="/info/gdpr" class="hover:text-white transition">GDPR i brisanje profila</a></li>
                    </ul>
                </div>
            </div>

            {{-- PAYMENT --}}
            <div class="lg:col-span-3">
                <div class="text-white font-semibold mb-5 text-lg">Plaćanje</div>

                <div class="rounded-2xl bg-white/5 border border-white/10 p-6">
                    <div class="text-base text-slate-300 font-semibold">
                        Načini plaćanja
                    </div>
                    <p class="mt-3 text-base text-slate-400 leading-normal">
                        Kartice i online plaćanja (Stripe / CorvusPay / WSPay).
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <span class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-sm text-slate-300">Visa</span>
                        <span class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-sm text-slate-300">Mastercard</span>
                        <span class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-sm text-slate-300">Apple Pay</span>
                        <span class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-sm text-slate-300">Google Pay</span>
                    </div>
                </div>

                <div class="mt-7 text-sm text-slate-500 leading-normal">
                    Korisnik je odgovoran za sadržaj i pripremu rublja. Reklamacije prema Uvjetima korištenja.
                </div>
            </div>
        </div>

        {{-- BOTTOM BAR --}}
        <div class="mt-16 pt-8 border-t border-white/10 flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
            <p class="text-sm text-slate-500">
                © {{ date('Y') }} FreshWash. Sva prava pridržana.
            </p>

            <div class="flex flex-wrap gap-6 text-sm text-slate-500">
                <a href="/info/odgovornost" class="hover:text-slate-300 transition">Odgovornost</a>
                <a href="/info/nacini-placanja" class="hover:text-slate-300 transition">Načini plaćanja</a>
                <a href="/info/privatnost" class="hover:text-slate-300 transition">Privatnost</a>
            </div>
        </div>
    </div>
</footer>
