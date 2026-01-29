<script setup lang="ts">
import MenuMain from '@/pages/front/components/MenuMain.vue';

const props = withDefaults(
    defineProps<{
        title: string;
        showShare?: boolean;
        showApp?: boolean;
        appHref?: string;
        isAuthenticated?: boolean;
        showMenu?: boolean;
        showThemeToggle?: boolean;
    }>(),
    {
        title: 'FreshWash',
        showShare: false,
        showApp: true,
        appHref: '/',
        isAuthenticated: false,
        showMenu: true,
        showThemeToggle: true,
    },
);
</script>

<template>
    <!-- Header -->
    <div class="header header-auto-show header-fixed header-logo-center">
        <a href="#" class="header-title">{{ props.title }}</a>

        <a
            v-if="props.showMenu"
            href="#"
            data-menu="menu-main"
            class="header-icon header-icon-1"
            aria-label="Menu"
        >
            <i class="fas fa-bars"></i>
        </a>

        <template v-if="props.showThemeToggle">
            <a
                href="#"
                data-toggle-theme
                class="header-icon header-icon-4 show-on-theme-dark"
                aria-label="Light mode"
            >
                <i class="fas fa-sun"></i>
            </a>
            <a
                href="#"
                data-toggle-theme
                class="header-icon header-icon-4 show-on-theme-light"
                aria-label="Dark mode"
            >
                <i class="fas fa-moon"></i>
            </a>
        </template>

        <a
            v-if="props.showApp && props.isAuthenticated"
            :href="props.appHref"
            class="header-icon header-icon-3"
            aria-label="App"
        >
            <i class="fas fa-dashboard text-cyan-600"></i>
        </a>

        <!--        <a-->
        <!--            v-if="props.showShare"-->
        <!--            href="#"-->
        <!--            data-menu="menu-share"-->
        <!--            class="header-icon header-icon-3"-->
        <!--            aria-label="Share"-->
        <!--        >-->
        <!--            <i class="fas fa-share-alt"></i>-->
        <!--        </a>-->
    </div>

    <!-- Page title -->
    <div class="page-title page-title-fixed">
        <h1>{{ props.title }}</h1>

        <a
            v-if="props.showShare"
            href="#"
            class="page-title-icon bg-theme color-theme shadow-xl"
            data-menu="menu-share"
        >
            <i class="fa fa-share-alt"></i>
        </a>

        <a
            v-if="props.showApp"
            :href="props.appHref"
            class="page-title-icon color-theme bg-sky-800/80 shadow-xl"
        >
            <i class="fa fa-dashboard text-white"></i>
        </a>

        <a
            v-if="props.showThemeToggle"
            href="#"
            class="page-title-icon bg-theme color-theme show-on-theme-light shadow-xl"
            data-toggle-theme
        >
            <i class="fa fa-moon"></i>
        </a>
        <a
            v-if="props.showThemeToggle"
            href="#"
            class="page-title-icon bg-theme color-theme show-on-theme-dark shadow-xl"
            data-toggle-theme
        >
            <i class="fa fa-lightbulb color-yellow-dark"></i>
        </a>

        <a
            v-if="props.showMenu"
            href="#"
            class="page-title-icon bg-theme color-theme shadow-xl"
            data-menu="menu-main"
        >
            <i class="fa fa-bars"></i>
        </a>
    </div>
    <div class="page-title-clear"></div>
</template>