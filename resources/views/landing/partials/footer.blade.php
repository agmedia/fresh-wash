<footer class="bg-gray-900 text-gray-300">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid gap-10 md:grid-cols-4">
            {{-- BRAND / SHORT --}}
            <div>
                <div class="text-white font-extrabold text-lg mb-3">FreshWash</div>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Pametne veš-mašine po gradu. Rezervacija, online plaćanje i otključavanje QR kodom.
                </p>

                <div class="mt-4 flex items-center gap-3 text-sm">
                    <a href="/?openApp=1" class="px-4 py-2 rounded-md bg-blue-800 text-white hover:text-blue-300">Otvori aplikaciju</a>
                    <a href="/info/kontakt" class="px-4 py-2 rounded-md bg-blue-800 text-white hover:text-blue-300">Kontakt</a>
                </div>
            </div>

            {{-- LEGAL --}}
            <div>
                <div class="text-white font-semibold mb-3">Pravno</div>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="/info/uvjeti" class="hover:text-white">Uvjeti korištenja</a></li>
                    <li><a href="/info/privatnost" class="hover:text-white">Pravila privatnosti</a></li>
                    <li><a href="/info/cookies" class="hover:text-white">Kolačići (Cookies)</a></li>
                    <li><a href="/info/gdpr" class="hover:text-white">GDPR i brisanje profila</a></li>
                </ul>
            </div>

            {{-- BLOG --}}
            <div>
                <div class="text-white font-semibold mb-3">Blog</div>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="/info/blog" class="hover:text-white">Svi članci</a></li>
                    <li><a href="/info/blog/kategorija/savjeti" class="hover:text-white">Savjeti</a></li>
                    <li><a href="/info/blog/kategorija/odrzavanje" class="hover:text-white">Održavanje</a></li>
                    <li><a href="/info/blog/kategorija/novosti" class="hover:text-white">Novosti</a></li>
                </ul>

                <div class="mt-4">
                    <a href="/info/faq" class="inline-flex items-center text-sm text-white hover:text-blue-300">
                        FAQ
                        <span class="ml-2 opacity-60">→</span>
                    </a>
                </div>
            </div>

            {{-- PAYMENT / RESPONSIBILITY --}}
            <div>
                <div class="text-white font-semibold mb-3">Plaćanje i odgovornost</div>

                <div class="text-sm text-gray-400 space-y-3">
                    <div>
                        <div class="text-gray-200 font-medium mb-1">Načini plaćanja</div>
                        <p class="leading-relaxed">
                            Kartice i online plaćanja (npr. Stripe/CorvusPay/WSPay).
                        </p>
                    </div>

                    <div>
                        <div class="text-gray-200 font-medium mb-1">Odgovornost</div>
                        <p class="leading-relaxed">
                            Korisnik je odgovoran za sadržaj i pripremu rublja. Sustav pruža pristup uređaju i evidenciju
                            transakcija; eventualne reklamacije rješavaju se prema Uvjetima korištenja.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-10 pt-6 border-t border-gray-800 flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} FreshWash. Sva prava pridržana.
            </p>

            <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                <a href="/info/odgovornost" class="hover:text-gray-300">Odgovornost</a>
                <a href="/info/nacini-placanja" class="hover:text-gray-300">Načini plaćanja</a>
                <a href="/info/privatnost" class="hover:text-gray-300">Privatnost</a>
            </div>
        </div>
    </div>
</footer>