<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import TopBar from './components/TopBar.vue';
import MenuMain from '@/pages/front/components/MenuMain.vue';
import { onMounted, ref } from 'vue';

const page = usePage();

// dolazi iz AppController::home
const isAuthenticated = computed<boolean>(() =>
    Boolean((page.props as any).isAuthenticated),
);

//
const canInstall = ref(false);
let deferredPrompt: any = null;

const isIos = () => /iphone|ipad|ipod/i.test(navigator.userAgent);
const isStandalone = () =>
    (window.matchMedia &&
        window.matchMedia('(display-mode: standalone)').matches) ||
    (navigator as any).standalone === true;

const showInstallHintIos = ref(false);

const installPwa = async () => {
    if (!deferredPrompt) return;
    deferredPrompt.prompt();
    await deferredPrompt.userChoice;
    deferredPrompt = null;
    canInstall.value = false;
};

// ako je user logiran, PWA entry treba voditi u /app
const appHref = '/app';

const infoBase = '/info';
const loginHref = '/login';
const registerHref = '/register';
const logoutHref = '/logout';

onMounted(() => {
    // iOS nema beforeinstallprompt -> pokaži uputu ako nije instalirano
    if (isIos() && !isStandalone()) {
        showInstallHintIos.value = true;
    }

    window.addEventListener('beforeinstallprompt', (e: any) => {
        e.preventDefault();
        deferredPrompt = e;
        canInstall.value = true;
    });

    window.addEventListener('appinstalled', () => {
        deferredPrompt = null;
        canInstall.value = false;
        showInstallHintIos.value = false;
    });
});
</script>

<template>
    <!-- Theme-based PWA homepage (AppKit style) -->
    <!-- <div id="preloader">
        <div class="spinner-border color-highlight" role="status"></div>
    </div> -->

    <div id="page">
        <TopBar
            title="FreshWash"
            :app-href="appHref"
            :isAuthenticated="isAuthenticated"
        />

        <div class="page-content footer-bar-clear">
            <div class="card card-style shadow-xl">
                <div class="content">
                    <p class="color-highlight font-600 mb-n1">PWA aplikacija</p>
                    <h1 class="font-24 font-700 mb-2">
                        Rezerviraj veš mašinu, plati i otključaj
                        <i
                            class="fa fa-star mt-n2 font-30 color-yellow-dark scale-box float-end me-2"
                        ></i>
                    </h1>
                    <p class="mb-1">
                        Odaberi lokaciju, mašinu i servis. Sustav izračuna
                        cijenu (PDV uključen), nakon plaćanja dobiješ QR kod za
                        pristup.
                    </p>

                    <div class="d-flex mt-3 flex-wrap gap-2">
                        <template v-if="isAuthenticated">
                            <Link
                                :href="appHref"
                                class="btn btn-m font-13 font-600 gradient-highlight rounded-s"
                            >
                                Otvori aplikaciju
                            </Link>
                            <div class="mt-3" v-if="canInstall">
                                <button
                                    type="button"
                                    class="btn btn-m font-13 font-700 bg-highlight w-100 rounded-s"
                                    @click="installPwa"
                                >
                                    Instaliraj aplikaciju (PWA)
                                </button>
                            </div>

                            <div class="mt-3" v-else-if="showInstallHintIos">
                                <a
                                    href="#"
                                    class="btn btn-m font-13 font-700 btn-border border-gray-dark color-gray-dark w-100 rounded-s"
                                    data-menu="menu-install-pwa-ios"
                                >
                                    Instaliraj na iPhone (Add to Home Screen)
                                </a>
                            </div>
                            <Link
                                :href="logoutHref"
                                method="post"
                                as="button"
                                class="btn btn-m font-13 font-600 btn-border border-gray-dark color-gray-dark rounded-s"
                            >
                                Odjava
                            </Link>
                        </template>

                        <template v-else>
                            <Link
                                :href="loginHref"
                                class="btn btn-m font-13 font-600 gradient-highlight rounded-s"
                            >
                                Prijava
                            </Link>
                            <Link
                                :href="registerHref"
                                class="btn btn-m font-13 font-600 btn-border border-gray-dark color-gray-dark rounded-s"
                            >
                                Registracija
                            </Link>
                        </template>
                    </div>
                </div>
            </div>

            <div class="card card-style">
                <div class="content">
                    <p class="color-highlight font-600 mb-n1">Info web</p>
                    <h2 class="font-18 font-700 mb-2">Saznaj više</h2>

                    <div class="row mb-0 text-center">
                        <div class="col-4">
                            <a :href="`${infoBase}/kako-radi`" class="d-block">
                                <i
                                    class="fa fa-circle-question font-26 color-blue-dark"
                                ></i>
                                <p class="font-12 mt-2 mb-0">Kako radi</p>
                            </a>
                        </div>
                        <div class="col-4">
                            <a :href="`${infoBase}/lokacije`" class="d-block">
                                <i
                                    class="fa fa-location-dot font-26 color-green-light"
                                ></i>
                                <p class="font-12 mt-2 mb-0">Lokacije</p>
                            </a>
                        </div>
                        <div class="col-4">
                            <a :href="`${infoBase}/cjenik`" class="d-block">
                                <i
                                    class="fa fa-euro-sign font-26 color-yellow-dark"
                                ></i>
                                <p class="font-12 mt-2 mb-0">Cjenik</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-style">
                <div class="content">
                    <div class="d-flex">
                        <div class="align-self-center">
                            <i
                                class="fa fa-qrcode color-highlight font-34 me-3"
                            ></i>
                        </div>
                        <div class="align-self-center">
                            <h5 class="mb-1">QR pristup</h5>
                            <p class="font-13 mb-0 opacity-70">
                                Nakon plaćanja dobiješ ograničeni QR token za
                                otključavanje.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-style">
                <div class="content">
                    <p class="font-12 mb-2 opacity-70">Brzi linkovi</p>
                    <div class="list-group list-custom list-group-m rounded-xs">
                        <a class="list-group-item" :href="`${infoBase}/faq`">
                            <i class="fa fa-circle-info color-blue-dark"></i>
                            <span>FAQ</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                        <a
                            class="list-group-item"
                            :href="`${infoBase}/kontakt`"
                        >
                            <i class="fa fa-envelope color-green-light"></i>
                            <span>Kontakt</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                        <a class="list-group-item" :href="`${infoBase}/blog`">
                            <i class="fa fa-newspaper color-yellow-dark"></i>
                            <span>Blog</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card card-style">
                <div class="content text-center">
                    <p class="font-12 mb-0 opacity-70">Admin je odvojeno na</p>
                    <p class="mb-0"><span class="font-600">/admin</span></p>
                </div>
            </div>
        </div>

        <!-- AppKit menus (loaded from public/front-theme/*.html) -->
        <div
            id="menu-main"
            class="menu menu-box-left rounded-0"
            data-menu-width="280"
        >
            <MenuMain active="nav-welcome" />
        </div>

        <div
            id="menu-colors"
            class="menu menu-box-bottom rounded-m"
            data-menu-load="/front-theme/menu-colors.html"
            data-menu-height="480"
        ></div>
    </div>
</template>