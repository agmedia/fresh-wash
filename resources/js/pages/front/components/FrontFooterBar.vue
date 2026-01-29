<script setup lang="ts">
import { computed } from 'vue';

type Key = 'home' | 'info' | 'lokacije' | 'cjenik' | 'app' | 'profil' | 'menu';

const props = withDefaults(
    defineProps<{
        active?: Key;
        isAuthenticated?: boolean;
        infoBase?: string;
        appHref?: string;
        loginHref?: string;
    }>(),
    {
        active: 'home',
        isAuthenticated: false,
        infoBase: '/info',
        appHref: '/app',
        loginHref: '/login',
    },
);

const cls = (key: Key, extra = '') =>
    computed(() =>
        [extra, props.active === key ? 'active-nav' : '']
            .filter(Boolean)
            .join(' '),
    ).value;
</script>

<template>
    <div id="footer-bar" class="footer-bar-6">
        <a :href="`${appHref}/narudzbe`" :class="cls('orders')">
            <i class="fa fa-file"></i><span>Narudžbe</span>
        </a>

        <a :href="`${infoBase}/lokacije`" :class="cls('lokacije')">
            <i class="fa fa-location-dot"></i><span>Lokacije</span>
        </a>

        <a :href="`/app`" :class="cls('home', 'circle-nav')">
            <i class="fa fa-home"></i><span>Home</span>
        </a>

        <a :href="`/`" :class="cls('web')">
            <i class="fa fa-globe"></i><span>Info</span>
        </a>

        <a
            v-if="isAuthenticated"
            :href="`${appHref}/profil`"
            :class="cls('app')"
        >
            <i class="fa fa-user"></i><span>Profil</span>
        </a>
        <a v-else :href="loginHref" :class="cls('app')">
            <i class="fa fa-right-to-bracket"></i><span>Prijava</span>
        </a>

        <!-- opciono: ako želiš “Menu” kao 5. item umjesto “App/Prijava” -->
        <!--
        <a href="#" data-menu="menu-main" :class="cls('menu')">
            <i class="fa fa-bars"></i><span>Menu</span>
        </a>
        -->
    </div>
</template>
