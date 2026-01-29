<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

type MenuItem = {
    id: string;
    label: string;
    href: string;
    iconClass: string;
    badge?: string;
};

const props = defineProps<{
    active?: string; // npr. 'nav-welcome' ili 'nav-locations'
}>();

const page = usePage();
const isAuth = computed(() => Boolean((page.props as any).auth?.user));

const items = computed<MenuItem[]>(() => [
    {
        id: 'nav-welcome',
        label: 'Home',
        href: '/',
        iconClass: 'fa fa-heart gradient-red color-white',
    },
    {
        id: 'nav-app',
        label: 'Aplikacija',
        href: '/app',
        iconClass: 'fa fa-mobile gradient-green color-white',
    },
    {
        id: 'nav-locations',
        label: 'Lokacije',
        href: '/info/lokacije',
        iconClass: 'fa fa-location-dot gradient-blue color-white',
    },
    {
        id: 'nav-pricing',
        label: 'Cjenik',
        href: '/info/cjenik',
        iconClass: 'fa fa-euro-sign gradient-brown color-white',
    },
    {
        id: 'nav-faq',
        label: 'FAQ',
        href: '/info/faq',
        iconClass: 'fa fa-circle-question gradient-teal color-white',
    },
    {
        id: 'nav-contact',
        label: 'Kontakt',
        href: '/info/kontakt',
        iconClass: 'fa fa-envelope gradient-magenta color-white',
    },
    {
        id: 'nav-blog',
        label: 'Blog',
        href: '/info/blog',
        iconClass: 'fa fa-newspaper gradient-dark color-white',
    },
]);

const year = new Date().getFullYear();

const isActive = (id: string) => (props.active ?? '') === id;
</script>

<template>
    <div class="d-flex flex-column h-100">
        <!-- VAŽNO: zadržavamo DOM strukturu koju AppKit očekuje -->
        <div class="card rounded-0 bg-6" data-card-height="150">
            <div class="card-top">
                <a
                    href="#"
                    class="close-menu icon-40 notch-clear float-end me-2 mt-3 text-center"
                    aria-label="Close"
                >
                    <i class="fa fa-times color-white"></i>
                </a>
            </div>
            <div class="card-bottom">
                <h1 class="color-white mb-n1 font-28 ps-3">FreshWash</h1>
                <p class="font-12 color-white mb-2 ps-3 opacity-50">
                    Pametne veš-mašine po gradu
                </p>
            </div>
            <div class="card-overlay bg-gradient"></div>
        </div>

        <div class="mt-4"></div>

        <h6 class="menu-divider">Navigacija</h6>
        <div class="list-group list-custom-small list-menu">
            <template v-for="it in items" :key="it.id">
                <!-- AppKit markup (a tag + ikone), ali link ide preko Inertia -->
                <Link
                    :id="it.id"
                    :href="it.href"
                    :class="isActive(it.id) ? 'active-nav' : ''"
                >
                    <i :class="it.iconClass"></i>
                    <span>{{ it.label }}</span>
                </Link>
            </template>
        </div>

        <h6 class="menu-divider mt-4">Račun</h6>
        <div class="list-group list-custom-small list-menu">
            <template v-if="isAuth">
                <Link href="/app/profil">
                    <i class="fa fa-user gradient-highlight color-white"></i>
                    <span>Profil</span>
                    <i class="fa fa-angle-right"></i>
                </Link>
            </template>
            <template v-else>
                <Link href="/login">
                    <i class="fa fa-sign-in gradient-green color-white"></i>
                    <span>Prijava</span>
                    <i class="fa fa-angle-right"></i>
                </Link>
                <Link href="/register">
                    <i class="fa fa-user-plus gradient-blue color-white"></i>
                    <span>Registracija</span>
                    <i class="fa fa-angle-right"></i>
                </Link>
            </template>
            <a
                href="#"
                data-toggle-theme
                data-trigger-switch="switch-dark-mode"
            >
                <i class="fa fa-moon gradient-dark color-white"></i>
                <span>Dark Mode</span>
                <div class="custom-control small-switch ios-switch">
                    <input
                        data-toggle-theme
                        type="checkbox"
                        class="ios-input"
                        id="toggle-dark-menu"
                    />
                    <label
                        class="custom-control-label"
                        for="toggle-dark-menu"
                    ></label>
                </div>
            </a>
        </div>

        <!-- FOOTER (ovo ide dolje) -->
        <h6 class="menu-divider font-10 mt-auto opacity-60">
            Made with <i class="fa fa-heart color-red-dark px-1"></i> in
            <span class="copyright-year">{{ year }}</span> by <a href="https://agmedia.hr">AGM</a>
        </h6>
    </div>

</template>