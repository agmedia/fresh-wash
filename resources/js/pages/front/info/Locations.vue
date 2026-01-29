<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watchEffect } from 'vue';
import TopBar from '@/pages/front/components/TopBar.vue';
import FrontFooterBar from '@/pages/front/components/FrontFooterBar.vue';

const page = usePage();

const isAuthenticated = computed(() => Boolean((page.props as any).auth?.user));

type Location = {
    id: number;
    name: string;
    slug: string;
    address_line1: string | null;
    address_line2: string | null;
    postal_code: string | null;
    city: string | null;
    country_code: string | null;
    lat: string | number | null;
    lng: string | number | null;
};

const props = defineProps<{
    locations: Location[];
}>();

const activeId = ref<number | null>(props.locations?.[0]?.id ?? null);

const active = computed(() => {
    return (
        props.locations.find((l) => l.id === activeId.value) ??
        props.locations?.[0] ??
        null
    );
});

const fullAddress = (l: Location) => {
    const parts = [
        l.address_line1,
        l.address_line2,
        [l.postal_code, l.city].filter(Boolean).join(' '),
        l.country_code,
    ].filter(Boolean);

    return parts.join(', ');
};

const mapSrc = computed(() => {
    const l = active.value;
    if (!l) return '';

    const lat = l.lat ? Number(l.lat) : null;
    const lng = l.lng ? Number(l.lng) : null;

    if (lat && lng) {
        return `https://www.google.com/maps?q=${encodeURIComponent(`${lat},${lng}`)}&z=16&output=embed`;
    }

    const addr = fullAddress(l);
    return `https://www.google.com/maps?q=${encodeURIComponent(addr)}&z=16&output=embed`;
});

const mapsLink = computed(() => {
    const l = active.value;
    if (!l) return '#';

    const lat = l.lat ? Number(l.lat) : null;
    const lng = l.lng ? Number(l.lng) : null;

    if (lat && lng)
        return `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;

    return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(fullAddress(l))}`;
});

// UX: ako nema lokacija, reset
watchEffect(() => {
    if (!props.locations?.length) activeId.value = null;
});
</script>

<template>
    <div id="page">
        <!-- možeš kasnije izvući topbar/page-title u komponentu -->
        <TopBar title="Lokacije" />

        <div class="page-content">
            <div v-if="!locations?.length" class="card card-style">
                <div class="content">
                    <p class="mb-0">Trenutno nema aktivnih lokacija.</p>
                </div>
            </div>

            <template v-else>
                <div class="card card-style shadow-xl">
                    <div class="content">
                        <p class="color-highlight font-600 mb-n1">Karta</p>

                        <div
                            class="rounded-m overflow-hidden"
                            style="height: 320px"
                        >
                            <iframe
                                v-if="mapSrc"
                                :src="mapSrc"
                                width="100%"
                                height="320"
                                style="border: 0"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            />
                        </div>

                        <div
                            class="d-flex justify-content-between align-items-center mt-3"
                        >
                            <div>
                                <h2 class="font-16 font-700 mb-1">
                                    {{ active?.name }}
                                </h2>
                                <p class="font-12 mb-0 opacity-70">
                                    {{ active ? fullAddress(active) : '' }}
                                </p>
                            </div>

                            <a
                                class="btn btn-s font-12 font-600 gradient-highlight rounded-s"
                                :href="mapsLink"
                                target="_blank"
                                rel="noopener"
                            >
                                Navigacija
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card card-style">
                    <div class="content">
                        <p class="color-highlight font-600 mb-n1">Popis</p>
                        <h2 class="font-18 font-700 mb-2">Odaberi lokaciju</h2>

                        <div
                            class="list-group list-custom list-group-m rounded-xs"
                        >
                            <a
                                v-for="l in locations"
                                :key="l.id"
                                href="#"
                                class="list-group-item"
                                :class="
                                    activeId === l.id
                                        ? 'bg-highlight bg-opacity-10'
                                        : ''
                                "
                                @click.prevent="activeId = l.id"
                            >
                                <i
                                    class="fa fa-location-dot color-green-light"
                                ></i>
                                <span>
                                    {{ l.name }}
                                    <br />
                                    <small class="opacity-70">{{
                                        fullAddress(l)
                                    }}</small>
                                </span>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- theme menus -->
        <div
            id="menu-main"
            class="menu menu-box-left rounded-0"
            data-menu-width="280"
            data-menu-load="/front-theme/menu-main.html"
        ></div>

        <div
            id="menu-share"
            class="menu menu-box-bottom rounded-m"
            data-menu-load="/front-theme/menu-share.html"
            data-menu-height="370"
        ></div>

        <!-- Footer bar -->
        <FrontFooterBar :active="'lokacije'"
            :is-authenticated="isAuthenticated"
        />
    </div>
</template>
