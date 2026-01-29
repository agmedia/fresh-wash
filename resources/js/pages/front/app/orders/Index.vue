<script setup lang="ts">
import FrontFooterBar from '@/pages/front/components/FrontFooterBar.vue';
import TopBar from '@/pages/front/components/TopBar.vue';
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

type Order = {
    id: number;
    code: string; // ORD-2026-000123
    status: 'pending' | 'paid' | 'cancelled' | 'refunded' | 'completed';
    total_gross: string | number; // 12.34
    currency: string; // EUR
    created_at: string; // ISO ili formatirano
    location?: { name: string } | null;
    locker?: { code: string; name?: string | null } | null; // ili "machine"
    service?: { name: string } | null;
};

const props = defineProps<{
    pending: Order[];
    paid: Order[];
}>();

const activeTab = ref<'pending' | 'paid'>('pending');
const list = computed(() =>
    activeTab.value === 'pending' ? props.pending : props.paid,
);

const page = usePage();

const isAuthenticated = computed(() => Boolean((page.props as any).auth?.user));

const money = (o: Order) => {
    const n = Number(o.total_gross ?? 0);
    return `${n.toFixed(2)} ${o.currency ?? 'EUR'}`;
};

const subtitle = (o: Order) => {
    const parts = [
        o.location?.name,
        o.locker?.name ?? o.locker?.code,
        o.service?.name,
    ].filter(Boolean);
    return parts.join(' • ');
};

// AppKit “menu” koristi dataset i custom.js; ovo samo puni modal sadržaj.
const selected = ref<Order | null>(null);
const openInvoice = (o: Order) => {
    selected.value = o;
    // AppKit open menu (custom.js sluša data-menu attr). Ako ti ne radi,
    // možeš ručno triggerati klik na element s data-menu="menu-invoice".
};
</script>

<template>
    <div id="page">
        <TopBar title="Narudžbe" />

        <div class="page-content footer-bar-clear">
            <!-- Header card + CTA -->
            <div class="card card-style shadow-xl">
                <div class="content">
                    <div class="d-flex">
                        <div class="align-self-center">
                            <p class="color-highlight font-600 mb-n1">
                                PWA aplikacija
                            </p>
                            <h1 class="font-22 font-800 mb-0">Moje narudžbe</h1>
                            <p class="font-12 mb-0 opacity-70">
                                Pregled aktivnih i završenih narudžbi.
                            </p>
                        </div>
                        <div class="align-self-center ms-auto">
                            <a
                                href="/app/order/new"
                                class="btn btn-s font-12 font-700 gradient-highlight rounded-s"
                            >
                                Nova narudžba
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div id="tab-group-1">
                <div class="card card-style bg-theme pb-0">
                    <div class="content mx-0 my-0 mb-0">
                        <div
                            class="tab-controls tabs-small"
                            data-highlight="bg-highlight"
                        >
                            <a
                                href="#"
                                :data-active="
                                    activeTab === 'pending' ? true : null
                                "
                                @click.prevent="activeTab = 'pending'"
                            >
                                Aktivne
                            </a>
                            <a
                                href="#"
                                :data-active="
                                    activeTab === 'paid' ? true : null
                                "
                                @click.prevent="activeTab = 'paid'"
                            >
                                Završene
                            </a>
                        </div>
                    </div>
                </div>

                <!-- LIST -->
                <div class="content mt-0">
                    <div v-if="!list.length" class="card card-style p-3">
                        <p class="font-12 mb-2 text-center opacity-70">
                            {{
                                activeTab === 'pending'
                                    ? 'Nema aktivnih narudžbi.'
                                    : 'Nema završenih narudžbi.'
                            }}
                        </p>
                        <a
                            href="/app/order/new"
                            class="btn btn-m font-13 font-700 gradient-highlight btn-full rounded-s"
                        >
                            Kreiraj novu narudžbu
                        </a>
                    </div>

                    <div v-else class="card card-style p-3">
                        <a
                            v-for="o in list"
                            :key="o.id"
                            href="#"
                            data-menu="menu-invoice"
                            class="d-flex"
                            @click.prevent="openInvoice(o)"
                        >
                            <div class="align-self-center">
                                <!-- ikona po statusu -->
                                <span
                                    class="icon icon-s color-white shadow-xxl rounded-sm"
                                    :class="
                                        o.status === 'paid' ||
                                        o.status === 'completed'
                                            ? 'gradient-green'
                                            : o.status === 'cancelled'
                                              ? 'gradient-red'
                                              : 'gradient-blue'
                                    "
                                >
                                    <i
                                        class="fa font-18"
                                        :class="
                                            o.status === 'paid' ||
                                            o.status === 'completed'
                                                ? 'fa-check'
                                                : o.status === 'cancelled'
                                                  ? 'fa-xmark'
                                                  : 'fa-clock'
                                        "
                                    ></i>
                                </span>
                            </div>

                            <div class="align-self-center">
                                <h5 class="mb-n1 font-15 ps-3">
                                    {{ o.code }}
                                </h5>
                                <span
                                    class="font-11 color-theme ps-3 opacity-70"
                                >
                                    {{ subtitle(o) || 'Narudžba' }}
                                </span>
                            </div>

                            <div class="align-self-center ms-auto text-end">
                                <h5
                                    class="color-theme font-15 font-800 d-block mb-n1"
                                >
                                    {{ money(o) }}
                                </h5>

                                <span
                                    class="font-11 d-block"
                                    :class="
                                        o.status === 'paid' ||
                                        o.status === 'completed'
                                            ? 'color-green-dark'
                                            : o.status === 'cancelled'
                                              ? 'color-red-dark'
                                              : 'color-blue-dark'
                                    "
                                >
                                    {{ o.created_at }}
                                    <i
                                        class="fa"
                                        :class="
                                            o.status === 'paid' ||
                                            o.status === 'completed'
                                                ? 'fa-check-circle'
                                                : o.status === 'cancelled'
                                                  ? 'fa-times-circle'
                                                  : 'fa-circle-notch'
                                        "
                                    ></i>
                                </span>
                            </div>
                        </a>

                        <div class="divider my-3"></div>
                        <p class="font-11 mb-0 text-center opacity-50">
                            Tap na narudžbu za detalje.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice / Details bottom sheet (AppKit menu) -->
        <div
            id="menu-invoice"
            class="menu menu-box-bottom rounded-m bg-white"
            data-menu-height="520"
        >
            <div class="menu-title">
                <p class="color-highlight font-600">
                    {{
                        selected?.status === 'paid' ||
                        selected?.status === 'completed'
                            ? 'Plaćeno'
                            : selected?.status === 'cancelled'
                              ? 'Otkazano'
                              : 'U tijeku'
                    }}
                </p>
                <h1 class="font-24">{{ selected?.code ?? 'Narudžba' }}</h1>
                <a href="#" class="close-menu"
                    ><i class="fa fa-times-circle"></i
                ></a>
            </div>

            <div class="content mb-0">
                <div class="row mb-0">
                    <div class="col-4"><p class="color-theme">Lokacija</p></div>
                    <div class="col-8">
                        <p class="text-end opacity-80">
                            {{ selected?.location?.name ?? '-' }}
                        </p>
                    </div>

                    <div class="col-12 pt-2">
                        <div class="divider mb-2"></div>
                    </div>

                    <div class="col-4"><p class="color-theme">Uređaj</p></div>
                    <div class="col-8">
                        <p class="text-end opacity-80">
                            {{
                                selected?.locker?.name ??
                                selected?.locker?.code ??
                                '-'
                            }}
                        </p>
                    </div>

                    <div class="col-12 pt-2">
                        <div class="divider mb-2"></div>
                    </div>

                    <div class="col-4"><p class="color-theme">Usluga</p></div>
                    <div class="col-8">
                        <p class="text-end opacity-80">
                            {{ selected?.service?.name ?? '-' }}
                        </p>
                    </div>

                    <div class="col-12 pt-2">
                        <div class="divider mb-2"></div>
                    </div>

                    <div class="col-4"><p class="color-theme">Iznos</p></div>
                    <div class="col-8">
                        <p class="text-end opacity-80">
                            {{ selected ? money(selected) : '-' }}
                        </p>
                    </div>

                    <div class="col-12 pt-2">
                        <div class="divider mb-2"></div>
                    </div>

                    <div class="col-4"><p class="color-theme">Status</p></div>
                    <div class="col-8">
                        <p
                            class="text-end"
                            :class="
                                selected?.status === 'paid' ||
                                selected?.status === 'completed'
                                    ? 'color-green-dark'
                                    : selected?.status === 'cancelled'
                                      ? 'color-red-dark'
                                      : 'color-blue-dark'
                            "
                        >
                            {{ selected?.status ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="pt-3 pb-1">
                    <a
                        v-if="selected && selected.status === 'pending'"
                        :href="`/app/orders/${selected.id}/pay`"
                        class="close-menu btn btn-l font-13 font-700 gradient-highlight btn-full rounded-s border-0"
                    >
                        Plati
                    </a>

                    <a
                        v-else
                        :href="selected ? `/app/orders/${selected.id}` : '#'"
                        class="close-menu btn btn-l font-13 font-700 gradient-green btn-full rounded-s border-0"
                    >
                        Detalji
                    </a>
                </div>

                <p class="font-11 mb-0 pt-2 text-center opacity-50">
                    Status i detalji se osvježavaju automatski.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <FrontFooterBar active="orders" :isAuthenticated="isAuthenticated" />

        <!-- Theme menu loader (ako ti već postoji globalno, ostavi samo jedan) -->
        <div
            id="menu-main"
            class="menu menu-box-left rounded-0"
            data-menu-width="280"
            data-menu-load="/front-theme/menu-main.html"
        ></div>
    </div>
</template>
