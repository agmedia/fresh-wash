<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

type Stats = {
    users?: number;
    locations?: number;
    lockers?: number;
    devices?: number;
    reservations?: number;
    orders?: number;
    payments?: number;
    revenue_today_gross?: number;
    revenue_today_net?: number;
    pending_orders?: number;
    pending_payments?: number;
    revenue_last_7_days?: Array<{ date: string; label: string; gross: number; net: number }>;
};

const page = usePage();

const stats = computed<Stats>(() => (page.props as any).stats ?? {});

// const series = computed(() => stats.value.revenue_last_7_days ?? []);
// const maxGross = computed(() => {
//     const vals = series.value.map((d) => Number(d.gross ?? 0));
//     return Math.max(1, ...vals);
// });
// const barHeight = (gross: number) => {
//     const g = Number(gross ?? 0);
//     return Math.round((g / maxGross.value) * 100);
// };

const money = (value: unknown) => {
    const n = Number(value ?? 0);
    return new Intl.NumberFormat('hr-HR', { style: 'currency', currency: 'EUR' }).format(isFinite(n) ? n : 0);
};

const number = (value: unknown) => {
    const n = Number(value ?? 0);
    return new Intl.NumberFormat('hr-HR').format(isFinite(n) ? n : 0);
};
</script>

<template>
    <AdminLayout title="Admin dashboard">
        <div class="space-y-6">
            <!-- Stat cards -->
            <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-4">
                <div class="rounded border border-gray-800 bg-neutral-900 p-4">
                    <div class="text-xs text-gray-400">Prihod danas (bruto)</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-100">{{ money(stats.revenue_today_gross) }}</div>
                    <div class="mt-2 text-xs text-gray-500">NET: {{ money(stats.revenue_today_net) }}</div>
                </div>

                <div class="rounded border border-gray-800 bg-neutral-900 p-4">
                    <div class="text-xs text-gray-400">Narudžbe</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-100">{{ number(stats.orders) }}</div>
                    <div class="mt-2 text-xs text-gray-500">Pending: {{ number(stats.pending_orders) }}</div>
                </div>

                <div class="rounded border border-gray-800 bg-neutral-900 p-4">
                    <div class="text-xs text-gray-400">Plaćanja</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-100">{{ number(stats.payments) }}</div>
                    <div class="mt-2 text-xs text-gray-500">Pending: {{ number(stats.pending_payments) }}</div>
                </div>

                <div class="rounded border border-gray-800 bg-neutral-900 p-4">
                    <div class="text-xs text-gray-400">Rezervacije</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-100">{{ number(stats.reservations) }}</div>
                    <div class="mt-2 text-xs text-gray-500">Ukupno</div>
                </div>

                <div class="rounded border border-gray-800 bg-neutral-900 p-4">
                    <div class="text-xs text-gray-400">Korisnici</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-100">{{ number(stats.users) }}</div>
                    <div class="mt-2 text-xs text-gray-500">Ukupno</div>
                </div>

                <div class="rounded border border-gray-800 bg-neutral-900 p-4">
                    <div class="text-xs text-gray-400">Lokacije</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-100">{{ number(stats.locations) }}</div>
                    <div class="mt-2 text-xs text-gray-500">Aktivne</div>
                </div>

                <div class="rounded border border-gray-800 bg-neutral-900 p-4">
                    <div class="text-xs text-gray-400">Ormarići / VešMašine</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-100">{{ number(stats.lockers) }}</div>
                    <div class="mt-2 text-xs text-gray-500">Ukupno</div>
                </div>

                <div class="rounded border border-gray-800 bg-neutral-900 p-4">
                    <div class="text-xs text-gray-400">Uređaji</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-100">{{ number(stats.devices) }}</div>
                    <div class="mt-2 text-xs text-gray-500">Ukupno</div>
                </div>
            </div>

            <!-- Revenue last 7 days -->
            <!-- <div class="rounded border border-gray-800 bg-neutral-900 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-gray-100">Prihod zadnjih 7 dana</div>
                        <div class="mt-1 text-xs text-gray-500">Bruto (EUR) • PDV 25% (NET je informativno)</div>
                    </div>
                    <div class="text-xs text-gray-400">
                        Max: {{ money(maxGross) }}
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-7 gap-2">
                    <div v-for="d in series" :key="d.date" class="flex flex-col items-stretch gap-2">
                        <div
                            class="relative h-28 rounded bg-gray-800"
                            :title="`${d.label} • bruto ${money(d.gross)} • net ${money(d.net)}`"
                        >
                            <div
                                class="absolute bottom-0 left-0 right-0 rounded bg-emerald-600/80"
                                :style="{ height: barHeight(d.gross) + '%' }"
                            />
                        </div>
                        <div class="text-center text-[11px] text-gray-400">{{ d.label }}</div>
                    </div>
                </div>

                <div v-if="!series.length" class="mt-3 text-xs text-gray-500">
                    Nema podataka za prikaz.
                </div>
            </div> -->

            <!-- Quick links -->
            <div class="rounded border border-gray-800 bg-neutral-900 p-4">
                <div class="text-sm font-semibold text-gray-100">Brzi linkovi</div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <Link
                        href="/admin/reservations"
                        class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-neutral-700"
                    >
                        Rezervacije
                    </Link>
                    <Link
                        href="/admin/orders"
                        class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-neutral-700"
                    >
                        Narudžbe
                    </Link>
                    <Link
                        href="/admin/payments"
                        class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-neutral-700"
                    >
                        Plaćanja
                    </Link>
                    <Link
                        href="/admin/locations"
                        class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-neutral-700"
                    >
                        Lokacije
                    </Link>
                    <Link
                        href="/admin/lockers"
                        class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-neutral-700"
                    >
                        Ormarići
                    </Link>
                    <Link
                        href="/admin/devices"
                        class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-neutral-700"
                    >
                        Uređaji
                    </Link>
                    <Link
                        href="/admin/services"
                        class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-neutral-700"
                    >
                        Usluge
                    </Link>
                    <Link
                        href="/admin/tariffs"
                        class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-neutral-700"
                    >
                        Tarife
                    </Link>
                    <Link
                        href="/admin/pages"
                        class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-neutral-700"
                    >
                        Stranice
                    </Link>
                    <Link
                        href="/admin/settings/roles"
                        class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-neutral-700"
                    >
                        Uloge & prava
                    </Link>
                </div>

                <div class="mt-3 text-xs text-gray-500">
                    * Ako ne vidiš brojke, provjeri da controller za dashboard šalje <code>stats</code> u Inertia props.
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
