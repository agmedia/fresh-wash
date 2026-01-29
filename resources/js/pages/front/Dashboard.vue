<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import TopBar from '@/pages/front/components/TopBar.vue';
import FrontFooterBar from '@/pages/front/components/FrontFooterBar.vue';

const page = usePage();
const user = (page.props as any)?.auth?.user;

const isAuthenticated = computed(() => Boolean((page.props as any).auth?.user));

// Routes (hardcoded for now)
const newOrderHref = '/app/nova-narudzba';
const ordersHref = '/app/narudzbe';
const profileHref = '/app/profil';
const supportHref = '/info/kontakt';
</script>

<template>
    <!-- <div id="preloader">
        <div class="spinner-border color-highlight" role="status"></div>
    </div> -->

    <div id="page" class="theme-light" data-highlight="highlight-red">
        <!-- Header -->
        <TopBar title="Dashboard" />

        <!-- Content -->
        <div class="page-content footer-bar-clear">
            <!-- Welcome card -->
            <div class="card card-style shadow-xl">
                <div class="content">
                    <p class="color-highlight font-600 mb-n1">Dobrodošao</p>
                    <h1 class="font-24 font-800 mb-1">
                        {{ user?.first_name ?? user?.name ?? 'Korisnik' }}
                        <i
                            class="fa fa-wave-square mt-n1 font-22 color-yellow-dark float-end me-2"
                        ></i>
                    </h1>
                    <p class="mb-0 opacity-80">
                        Ovdje ćeš vidjeti aktivne rezervacije, status plaćanja i
                        QR kod za otključavanje.
                    </p>
                </div>
            </div>

            <!-- Quick actions -->
            <div class="card card-style">
                <div class="content">
                    <div class="d-flex">
                        <div class="align-self-center">
                            <h2 class="mb-0">Brze akcije</h2>
                            <p class="font-13 mb-0 opacity-70">
                                Najčešće radnje
                            </p>
                        </div>
                        <div class="align-self-center ms-auto">
                            <i class="fa fa-bolt color-highlight font-24"></i>
                        </div>
                    </div>

                    <div class="row mt-3 mb-0 text-center">
                        <div class="col-4">
                            <Link :href="newOrderHref" class="d-block">
                                <i
                                    class="fa fa-plus-circle font-28 color-green-light"
                                ></i>
                                <p class="font-12 mt-2 mb-0">Nova</p>
                            </Link>
                        </div>
                        <div class="col-4">
                            <Link :href="ordersHref" class="d-block">
                                <i
                                    class="fa fa-receipt font-28 color-blue-dark"
                                ></i>
                                <p class="font-12 mt-2 mb-0">Narudžbe</p>
                            </Link>
                        </div>
                        <div class="col-4">
                            <Link :href="profileHref" class="d-block">
                                <i
                                    class="fa fa-user-circle font-28 color-yellow-dark"
                                ></i>
                                <p class="font-12 mt-2 mb-0">Profil</p>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status / placeholder cards -->
            <div class="card card-style">
                <div class="content">
                    <div class="d-flex">
                        <div class="align-self-center">
                            <p class="color-highlight font-600 mb-n1">Status</p>
                            <h2 class="mb-0">Aktivna rezervacija</h2>
                            <p class="mt-2 mb-0 opacity-70">
                                Još nema aktivne rezervacije. Kreiraj novu
                                narudžbu i dobit ćeš QR kod nakon plaćanja.
                            </p>
                        </div>
                        <div class="align-self-center ms-auto">
                            <i class="fa fa-qrcode font-34 color-highlight"></i>
                        </div>
                    </div>

                    <div class="divider mt-3 mb-3"></div>

                    <Link
                        :href="newOrderHref"
                        class="btn btn-m font-13 font-700 gradient-highlight w-100 rounded-s"
                    >
                        Kreiraj novu narudžbu
                    </Link>
                </div>
            </div>

            <!-- List-style shortcuts -->
            <div class="card card-style">
                <div class="content mb-0">
                    <div class="list-group list-custom list-group-m rounded-xs">
                        <Link class="list-group-item" :href="ordersHref">
                            <i class="fa fa-clock color-blue-dark"></i>
                            <span>Povijest narudžbi</span>
                            <i class="fa fa-angle-right"></i>
                        </Link>
                        <Link class="list-group-item" :href="profileHref">
                            <i class="fa fa-id-card color-yellow-dark"></i>
                            <span>Uredi profil</span>
                            <i class="fa fa-angle-right"></i>
                        </Link>
                        <a class="list-group-item" :href="supportHref">
                            <i class="fa fa-life-ring color-green-light"></i>
                            <span>Podrška</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card card-style">
                <div class="content text-center">
                    <p class="font-12 mb-0 opacity-70">Locker PWA</p>
                </div>
            </div>
        </div>

        <!-- Menus (optional) -->
        <div
            id="menu-main"
            class="menu menu-box-left rounded-0"
            data-menu-width="280"
            data-menu-load="/menu-main.html"
        ></div>

        <div
            id="menu-colors"
            class="menu menu-box-bottom rounded-m"
            data-menu-load="/front-theme/menu-colors.html"
            data-menu-height="480"
        ></div>

        <!-- Footer bar -->
        <FrontFooterBar :active="'home'" :is-authenticated="isAuthenticated" />
    </div>
</template>